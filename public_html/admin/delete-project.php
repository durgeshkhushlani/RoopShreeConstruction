<?php
require __DIR__ . '/includes/auth-check.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/upload-helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/dashboard.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $pdo->prepare('DELETE FROM projects WHERE id = :id');
    $stmt->execute(['id' => $id]);
    delete_project_files($id);
}

header('Location: /admin/dashboard.php?deleted=1');
exit;
