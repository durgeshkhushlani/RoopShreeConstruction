<?php
// Copy this file to mail.php (gitignored) and fill in real SMTP credentials when available.
// Until SMTP_HOST is set to a real value, contact-handler.php runs in DEV mode:
// it logs the composed message to storage/contact-log.txt instead of sending,
// so the form is fully testable before real credentials exist.

return [
    'smtp_host' => getenv('SMTP_HOST') ?: 'CHANGE_ME',
    'smtp_port' => getenv('SMTP_PORT') ?: 587,
    'smtp_user' => getenv('SMTP_USER') ?: 'CHANGE_ME',
    'smtp_pass' => getenv('SMTP_PASS') ?: 'CHANGE_ME',
    'smtp_secure' => getenv('SMTP_SECURE') ?: 'tls', // 'tls' or 'ssl'
    'from_email' => getenv('MAIL_FROM') ?: 'noreply@roopshreeconstruction.com',
    'from_name' => 'Roop Shree Construction Website',
    'to_email' => getenv('MAIL_TO') ?: 'CHANGE_ME',
];
