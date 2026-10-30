<?php
declare(strict_types=1);

require __DIR__ . '/newsletter-lib.php';

$config = newsletter_config();
if (!$config) {
    newsletter_redirect('not_ready');
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    newsletter_redirect('invalid');
}

try {
    $db = newsletter_db($config);
    if (!newsletter_rate_limit($db, $config)) {
        newsletter_redirect('rate_limited');
    }

    $honeypot = trim((string)($_POST['website'] ?? ''));
    $startedAt = (int)($_POST['form_started_at'] ?? 0);
    if ($honeypot !== '' || $startedAt <= 0 || time() - $startedAt < 1 || time() - $startedAt > 7200) {
        newsletter_redirect('invalid');
    }

    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    if (strlen($email) > 254 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        newsletter_redirect('invalid');
    }
    if (($_POST['age_confirmed'] ?? '') !== 'yes' || ($_POST['marketing_consent'] ?? '') !== 'yes' || ($_POST['privacy_consent'] ?? '') !== 'yes') {
        newsletter_redirect('invalid');
    }

    $consentVersion = (string)($_POST['consent_version'] ?? '');
    $expectedVersion = (string)($config['consent_version'] ?? '2026-09-07-v1');
    if (!hash_equals($expectedVersion, $consentVersion)) {
        newsletter_redirect('invalid');
    }

    $allowedRegions = ['', '北海道・東北', '関東', '中部', '近畿', '中国・四国', '九州・沖縄', '海外'];
    $region = trim((string)($_POST['region'] ?? ''));
    if (!in_array($region, $allowedRegions, true)) {
        $region = '';
    }
    $source = trim((string)($_POST['source'] ?? 'website'));
    if (!in_array($source, ['home', 'newsletter', 'campaign', 'website'], true)) {
        $source = 'website';
    }

    $token = newsletter_random_token();
    $tokenHash = newsletter_token_hash($token, $config);
    $now = newsletter_now();
    $expires = gmdate('Y-m-d\TH:i:s\Z', time() + (int)($config['confirmation_ttl'] ?? 172800));
    $referredBy = newsletter_valid_referrer($db, (string)($_POST['referral_code'] ?? ''));

    $stmt = $db->prepare('SELECT * FROM subscribers WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $existing = $stmt->fetch();

    if ($existing && $existing['status'] === 'active') {
        newsletter_redirect('check_email');
    }

    if ($existing) {
        $stmt = $db->prepare('UPDATE subscribers SET status="pending", region=?, source=?, consent_version=?, age_confirmed=1, marketing_consent=1, privacy_consent=1, subscribed_at=?, confirmed_at=NULL, unsubscribed_at=NULL, confirm_token_hash=?, confirm_expires_at=?, referred_by=?, provider_status="pending", updated_at=? WHERE id=?');
        $stmt->execute([$region, $source, $consentVersion, $now, $tokenHash, $expires, $referredBy, $now, (int)$existing['id']]);
    } else {
        $referralCode = newsletter_new_referral_code($db);
        $stmt = $db->prepare('INSERT INTO subscribers(email,status,region,source,consent_version,age_confirmed,marketing_consent,privacy_consent,subscribed_at,confirm_token_hash,confirm_expires_at,referral_code,referred_by,provider_status,updated_at) VALUES(?,"pending",?,?,?,1,1,1,?,?,?,?,?,"pending",?)');
        $stmt->execute([$email, $region, $source, $consentVersion, $now, $tokenHash, $expires, $referralCode, $referredBy, $now]);
    }

    $base = rtrim((string)$config['base_url'], '/');
    $confirmUrl = $base . '/newsletter/confirm.php?token=' . rawurlencode($token);
    $safeUrl = htmlspecialchars($confirmUrl, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $html = newsletter_email_shell(
        '<h1 style="font-size:25px;margin:0 0 14px">メール登録をご確認ください</h1>' .
        '<p style="line-height:1.8;color:#4f5864">下のボタンを開くと登録が完了します。確認前は広告・キャンペーンメールの配信対象になりません。</p>' .
        '<p style="margin:28px 0"><a href="' . $safeUrl . '" style="display:inline-block;padding:14px 22px;border-radius:999px;background:#17191e;color:#fff;text-decoration:none;font-weight:700">メール登録を確認する</a></p>' .
        '<p style="font-size:12px;color:#777f8b;line-height:1.7">このリンクは一定時間で無効になります。心当たりがない場合は操作せず、このメールを破棄してください。</p>'
    );
    $text = "ELFBAR Japan メール登録の確認\n\n次のURLを開くと登録が完了します。\n{$confirmUrl}\n\n心当たりがない場合は、このメールを破棄してください。";
    newsletter_send_mail($config, $email, '【ELFBAR Japan】メール登録をご確認ください', $html, $text);
    newsletter_redirect('check_email');
} catch (Throwable $error) {
    newsletter_log_error($config, $error);
    newsletter_redirect('not_ready');
}

