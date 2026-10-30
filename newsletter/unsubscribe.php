<?php
declare(strict_types=1);

require dirname(__DIR__) . '/api/newsletter-lib.php';

$config = newsletter_config();
if (!$config) {
    newsletter_redirect('not_ready');
}

try {
    $token = (string)($_GET['token'] ?? '');
    if (!preg_match('/^(\d+)\.([a-f0-9]{64})$/', $token, $matches)) {
        newsletter_redirect('invalid');
    }
    $id = (int)$matches[1];
    $signature = $matches[2];
    $db = newsletter_db($config);
    $stmt = $db->prepare('SELECT * FROM subscribers WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $subscriber = $stmt->fetch();
    if (!$subscriber) {
        newsletter_redirect('invalid');
    }
    $expected = newsletter_unsubscribe_token($id, (string)$subscriber['email'], $config);
    $expectedSignature = substr($expected, strpos($expected, '.') + 1);
    if (!hash_equals($expectedSignature, $signature)) {
        newsletter_redirect('invalid');
    }

    $now = newsletter_now();
    $stmt = $db->prepare('UPDATE subscribers SET status="unsubscribed", marketing_consent=0, unsubscribed_at=?, provider_status="removal_pending", updated_at=? WHERE id=?');
    $stmt->execute([$now, $now, $id]);

    $providerStatus = 'local_only';
    try {
        newsletter_remove_contact($config, (string)$subscriber['email']);
        $providerStatus = (($config['transport'] ?? '') === 'brevo' && !empty($config['brevo_list_id'])) ? 'removed' : 'local_only';
    } catch (Throwable $syncError) {
        newsletter_log_error($config, $syncError);
        $providerStatus = 'removal_error';
    }
    $stmt = $db->prepare('UPDATE subscribers SET provider_status=?, updated_at=? WHERE id=?');
    $stmt->execute([$providerStatus, newsletter_now(), $id]);

    newsletter_redirect('unsubscribed');
} catch (Throwable $error) {
    newsletter_log_error($config, $error);
    newsletter_redirect('invalid');
}

