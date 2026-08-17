<?php
// Copy this file to db.php (gitignored) and fill in real credentials.
// Local (Docker): values below already match docker-compose.yml.
// Production (Bluehost): replace with the live PostgreSQL credentials from cPanel.

$host = getenv('DB_HOST') ?: 'db';
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME') ?: 'roopshree';
$user = getenv('DB_USER') ?: 'roopshree';
$password = getenv('DB_PASSWORD') ?: 'roopshree_local_dev';

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    error_log('DB connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Site temporarily unavailable.');
}
