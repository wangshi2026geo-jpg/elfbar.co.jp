<?php
declare(strict_types=1);

require dirname(__DIR__) . '/api/newsletter-lib.php';

$config = newsletter_config();
if (!$config) {
    newsletter_redirect('not_ready');
}

try {
    $token = (string)($_GET['token'] ?? '');
    if (!preg_match('/^[A-Za-z0-9_-]{32,80}$/', $token)) {
        newsletter_redirect('invalid');
    }

    $db = newsletter_db($config);
    $tokenHash = newsletter_token_hash($token, $config);
    $stmt = $db->prepare('SELECT * FROM subscribers WHERE confirm_token_hash = ? AND status = "pending" LIMIT 1');
    $stmt->execute([$tokenHash]);
    $subscriber = $stmt->fetch();
    if (!$subscriber) {
        newsletter_redirect('invalid');
    }
    if (strtotime((string)$subscriber['confirm_expires_at']) < time()) {
        newsletter_redirect('expired');
    }

    $now = newsletter_now();
    $stmt = $db->prepare('UPDATE subscribers SET status="active", confirmed_at=?, confirm_token_hash=NULL, confirm_expires_at=NULL, provider_status="pending", updated_at=? WHERE id=? AND status="pending"');
    $stmt->execute([$now, $now, (int)$subscriber['id']]);

    $providerStatus = 'not_configured';
    try {
        newsletter_sync_contact($config, (string)$subscriber['email']);
        $providerStatus = (($config['transport'] ?? '') === 'brevo' && !empty($config['brevo_list_id'])) ? 'synced' : 'local_only';
    } catch (Throwable $syncError) {
        newsletter_log_error($config, $syncError);
        $providerStatus = 'sync_error';
    }
    $stmt = $db->prepare('UPDATE subscribers SET provider_status=?, updated_at=? WHERE id=?');
    $stmt->execute([$providerStatus, newsletter_now(), (int)$subscriber['id']]);

    $base = rtrim((string)$config['base_url'], '/');
    $unsubscribeToken = newsletter_unsubscribe_token((int)$subscriber['id'], (string)$subscriber['email'], $config);
    $unsubscribeUrl = $base . '/newsletter/unsubscribe.php?token=' . rawurlencode($unsubscribeToken);
    $inviteUrl = $base . '/newsletter.html?ref=' . rawurlencode((string)$subscriber['referral_code']);
    $safeUnsubscribe = htmlspecialchars($unsubscribeUrl, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $safeInvite = htmlspecialchars($inviteUrl, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $safeCode = htmlspecialchars((string)$subscriber['referral_code'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    $html = newsletter_email_shell(
        '<h1 style="font-size:25px;margin:0 0 14px">メール登録が完了しました</h1>' .
        '<p style="line-height:1.8;color:#4f5864">新商品、取扱先、大阪店、キャンペーン・抽選企画の情報をお届けします。</p>' .
        '<div style="margin:25px 0;padding:18px;border-radius:14px;background:#eef8ff"><b>あなたの紹介コード</b><br><span style="font-size:22px;letter-spacing:.08em">' . $safeCode . '</span><br><a href="' . $safeInvite . '">紹介用リンクを開く</a></div>' .
        '<p style="font-size:12px;color:#777f8b;line-height:1.7">紹介コードの発行・利用だけで特典や当選が確定することはありません。個別規約が公開されている企画に限り、その条件が適用されます。</p>' .
        '<p style="font-size:12px"><a href="' . $safeUnsubscribe . '">メール配信を停止する</a></p>'
    );
    $text = "ELFBAR Japan メール登録完了\n\n紹介コード: {$subscriber['referral_code']}\n紹介用リンク: {$inviteUrl}\n\n紹介コードだけで特典や当選が確定することはありません。\n配信停止: {$unsubscribeUrl}";
    try {
        newsletter_send_mail($config, (string)$subscriber['email'], '【ELFBAR Japan】メール登録が完了しました', $html, $text);
    } catch (Throwable $mailError) {
        newsletter_log_error($config, $mailError);
    }

    newsletter_redirect('confirmed', ['code' => (string)$subscriber['referral_code']]);
} catch (Throwable $error) {
    newsletter_log_error($config, $error);
    newsletter_redirect('invalid');
}

