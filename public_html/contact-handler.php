<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $phone === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /contact.php?status=error');
    exit;
}

$config = require __DIR__ . '/includes/mail.php';
$devMode = $config['smtp_host'] === 'CHANGE_ME' || $config['smtp_pass'] === 'ENTER_PASSWORD_HERE';

$body = "New enquiry from the website contact form:\n\n"
    . "Name: $name\n"
    . "Email: $email\n"
    . "Phone: " . ($phone !== '' ? $phone : '-') . "\n\n"
    . "Message:\n$message\n";

if ($devMode) {
    $logDir = __DIR__ . '/storage';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    file_put_contents(
        $logDir . '/contact-log.txt',
        '[' . date('Y-m-d H:i:s') . "] (DEV MODE — SMTP not configured, not actually sent)\n" . $body . "\n---\n",
        FILE_APPEND
    );
    header('Location: /contact.php?status=success');
    exit;
}

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_user'];
    $mail->Password = $config['smtp_pass'];
    $mail->SMTPSecure = $config['smtp_secure'];
    $mail->Port = $config['smtp_port'];

    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['to_email']);
    $mail->addReplyTo($email, $name);

    $mail->Subject = 'Website enquiry from ' . $name;
    $mail->Body = $body;

    $mail->send();
    header('Location: /contact.php?status=success');
} catch (Exception $e) {
    error_log('Contact form send failed: ' . $mail->ErrorInfo);
    header('Location: /contact.php?status=error');
}
exit;
