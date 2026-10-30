<?php
declare(strict_types=1);

function newsletter_config(): array
{
    static $config = null;
    if (is_array($config)) {
        return $config;
    }

    $path = getenv('ELFBAR_NEWSLETTER_CONFIG');
    if (!$path) {
        $path = dirname(__DIR__) . '/newsletter-private/config.php';
    }
    if (!is_file($path)) {
        return [];
    }

    $loaded = require $path;
    if (!is_array($loaded) || strlen((string)($loaded['secret'] ?? '')) < 32) {
        return [];
    }
    $config = $loaded;
    return $config;
}

function newsletter_redirect(string $status, array $extra = []): never
{
    $config = newsletter_config();
    $base = rtrim((string)($config['base_url'] ?? ''), '/');
    $query = http_build_query(array_merge(['status' => $status], $extra), '', '&', PHP_QUERY_RFC3986);
    header('Cache-Control: no-store');
    header('Referrer-Policy: no-referrer');
    header('X-Robots-Tag: noindex, nofollow');
    header('Location: ' . $base . '/newsletter.html?' . $query, true, 303);
    exit;
}

function newsletter_db(array $config): PDO
{
    $path = (string)($config['db_path'] ?? '');
    if ($path === '') {
        throw new RuntimeException('Database path is not configured.');
    }
    $dir = dirname($path);
    if (!is_dir($dir) && !mkdir($dir, 0700, true) && !is_dir($dir)) {
        throw new RuntimeException('Unable to create database directory.');
    }

    $db = new PDO('sqlite:' . $path, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    @chmod($path, 0600);
    $db->exec('PRAGMA journal_mode = WAL');
    $db->exec('PRAGMA foreign_keys = ON');
    $db->exec(
        'CREATE TABLE IF NOT EXISTS subscribers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL COLLATE NOCASE UNIQUE,
            status TEXT NOT NULL CHECK(status IN ("pending","active","unsubscribed")),
            region TEXT NOT NULL DEFAULT "",
            source TEXT NOT NULL DEFAULT "website",
            consent_version TEXT NOT NULL,
            age_confirmed INTEGER NOT NULL DEFAULT 0,
            marketing_consent INTEGER NOT NULL DEFAULT 0,
            privacy_consent INTEGER NOT NULL DEFAULT 0,
            subscribed_at TEXT NOT NULL,
            confirmed_at TEXT,
            unsubscribed_at TEXT,
            confirm_token_hash TEXT,
            confirm_expires_at TEXT,
            referral_code TEXT NOT NULL UNIQUE,
            referred_by TEXT,
            provider_status TEXT NOT NULL DEFAULT "pending",
            updated_at TEXT NOT NULL
        )'
    );
    $db->exec(
        'CREATE TABLE IF NOT EXISTS rate_limits (
            client_key TEXT PRIMARY KEY,
            window_started INTEGER NOT NULL,
            hits INTEGER NOT NULL
        )'
    );
    return $db;
}

function newsletter_now(): string
{
    return gmdate('Y-m-d\TH:i:s\Z');
}

function newsletter_base64url(string $bytes): string
{
    return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
}

function newsletter_random_token(int $bytes = 32): string
{
    return newsletter_base64url(random_bytes($bytes));
}

function newsletter_token_hash(string $token, array $config): string
{
    return hash_hmac('sha256', $token, (string)$config['secret']);
}

function newsletter_unsubscribe_token(int $id, string $email, array $config): string
{
    $payload = (string)$id;
    $signature = hash_hmac('sha256', $payload . '|' . strtolower($email), (string)$config['secret']);
    return $payload . '.' . $signature;
}

function newsletter_new_referral_code(PDO $db): string
{
    for ($i = 0; $i < 10; $i++) {
        $code = 'EBJP-' . strtoupper(substr(newsletter_base64url(random_bytes(7)), 0, 8));
        $stmt = $db->prepare('SELECT 1 FROM subscribers WHERE referral_code = ? LIMIT 1');
        $stmt->execute([$code]);
        if (!$stmt->fetchColumn()) {
            return $code;
        }
    }
    throw new RuntimeException('Unable to generate a referral code.');
}

function newsletter_valid_referrer(PDO $db, string $code): ?string
{
    $code = strtoupper(trim($code));
    if (!preg_match('/^EBJP-[A-Z0-9_-]{6,12}$/', $code)) {
        return null;
    }
    $stmt = $db->prepare('SELECT referral_code FROM subscribers WHERE referral_code = ? AND status = "active" LIMIT 1');
    $stmt->execute([$code]);
    $value = $stmt->fetchColumn();
    return is_string($value) ? $value : null;
}

function newsletter_rate_limit(PDO $db, array $config): bool
{
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $key = hash_hmac('sha256', $ip, (string)$config['secret']);
    $window = (int)($config['rate_limit_window'] ?? 3600);
    $max = (int)($config['rate_limit_max'] ?? 5);
    $now = time();

    $db->beginTransaction();
    try {
        $stmt = $db->prepare('SELECT window_started, hits FROM rate_limits WHERE client_key = ?');
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        if (!$row || ($now - (int)$row['window_started']) >= $window) {
            $stmt = $db->prepare('INSERT INTO rate_limits(client_key, window_started, hits) VALUES(?,?,1) ON CONFLICT(client_key) DO UPDATE SET window_started=excluded.window_started, hits=1');
            $stmt->execute([$key, $now]);
            $db->commit();
            return true;
        }
        if ((int)$row['hits'] >= $max) {
            $db->commit();
            return false;
        }
        $stmt = $db->prepare('UPDATE rate_limits SET hits = hits + 1 WHERE client_key = ?');
        $stmt->execute([$key]);
        $db->commit();
        return true;
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        throw $e;
    }
}

function newsletter_brevo_request(array $config, string $method, string $path, array $payload): array
{
    $apiKey = (string)($config['brevo_api_key'] ?? '');
    if ($apiKey === '' || !function_exists('curl_init')) {
        throw new RuntimeException('Brevo is not configured.');
    }
    $ch = curl_init('https://api.brevo.com/v3' . $path);
    $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => [
            'accept: application/json',
            'content-type: application/json',
            'api-key: ' . $apiKey,
        ],
    ]);
    $body = curl_exec($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    if ($body === false || $status < 200 || $status >= 300) {
        throw new RuntimeException('Brevo request failed: HTTP ' . $status . ' ' . $error);
    }
    $decoded = json_decode((string)$body, true);
    return is_array($decoded) ? $decoded : [];
}

function newsletter_send_mail(array $config, string $to, string $subject, string $html, string $text): void
{
    $transport = (string)($config['transport'] ?? 'file');
    $senderEmail = (string)($config['sender_email'] ?? 'Sales@elfbar.co.jp');
    $senderName = (string)($config['sender_name'] ?? 'ELFBAR Japan');
    $replyTo = (string)($config['reply_to'] ?? $senderEmail);

    if ($transport === 'file') {
        $logPath = (string)($config['mail_log'] ?? (sys_get_temp_dir() . '/elfbar-newsletter-mail.log'));
        $entry = "\n--- " . newsletter_now() . " ---\nTO: {$to}\nSUBJECT: {$subject}\n{$text}\n";
        if (file_put_contents($logPath, $entry, FILE_APPEND | LOCK_EX) === false) {
            throw new RuntimeException('Unable to write mail log.');
        }
        return;
    }

    if ($transport === 'brevo') {
        newsletter_brevo_request($config, 'POST', '/smtp/email', [
            'sender' => ['name' => $senderName, 'email' => $senderEmail],
            'replyTo' => ['email' => $replyTo, 'name' => $senderName],
            'to' => [['email' => $to]],
            'subject' => $subject,
            'htmlContent' => $html,
            'textContent' => $text,
            'tags' => ['elfbar-japan-newsletter-system'],
        ]);
        return;
    }

    if ($transport === 'php_mail') {
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $senderName . ' <' . $senderEmail . '>',
            'Reply-To: ' . $replyTo,
        ];
        if (!mail($to, $encodedSubject, $html, implode("\r\n", $headers))) {
            throw new RuntimeException('mail() failed.');
        }
        return;
    }

    throw new RuntimeException('Unsupported mail transport.');
}

function newsletter_sync_contact(array $config, string $email): void
{
    if (($config['transport'] ?? '') !== 'brevo' || empty($config['brevo_list_id'])) {
        return;
    }
    newsletter_brevo_request($config, 'POST', '/contacts', [
        'email' => $email,
        'listIds' => [(int)$config['brevo_list_id']],
        'updateEnabled' => true,
    ]);
}

function newsletter_remove_contact(array $config, string $email): void
{
    if (($config['transport'] ?? '') !== 'brevo' || empty($config['brevo_list_id'])) {
        return;
    }
    newsletter_brevo_request(
        $config,
        'POST',
        '/contacts/lists/' . (int)$config['brevo_list_id'] . '/contacts/remove',
        ['emails' => [$email]]
    );
}

function newsletter_log_error(array $config, Throwable $error): void
{
    $path = (string)($config['error_log'] ?? (sys_get_temp_dir() . '/elfbar-newsletter-error.log'));
    @file_put_contents($path, newsletter_now() . ' ' . $error->getMessage() . "\n", FILE_APPEND | LOCK_EX);
}

function newsletter_email_shell(string $content): string
{
    return '<!doctype html><html lang="ja"><head><meta charset="utf-8"></head><body style="margin:0;background:#f4f7fb;color:#1b1e24;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif"><div style="max-width:620px;margin:0 auto;padding:36px 18px"><div style="background:#fff;border-radius:22px;padding:34px;border:1px solid #e3e8ef"><p style="margin:0 0 24px;font-size:24px;font-weight:800;letter-spacing:.16em">ELFBAR</p>' . $content . '<hr style="border:0;border-top:1px solid #e7ebf0;margin:30px 0"><p style="color:#747b86;font-size:12px;line-height:1.7">送信者：ナインファイヤー株式会社<br>大阪府大阪市浪速区難波中三丁目7番17-508号<br>お問い合わせ：Sales@elfbar.co.jp</p></div></div></body></html>';
}
