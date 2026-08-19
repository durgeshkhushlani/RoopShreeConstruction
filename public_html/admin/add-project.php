<?php
require __DIR__ . '/includes/auth-check.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/upload-helper.php';

$errors = [];
$project = $_POST;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $type = $_POST['type'] ?? '';
    $status = $_POST['status'] ?? 'Available';
    $validTypes = ['Flat', 'Plot', 'Villa', 'Commercial'];
    $validStatuses = ['Available', 'Sold', 'Coming Soon'];

    if ($title === '') {
        $errors[] = 'Title is required.';
    }
    if (!in_array($type, $validTypes, true)) {
        $errors[] = 'Please choose a valid type.';
    }
    if (!in_array($status, $validStatuses, true)) {
        $errors[] = 'Please choose a valid status.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO projects (title, type, location, price, size, status, rera_number, short_desc, full_desc, featured)
             VALUES (:title, :type, :location, :price, :size, :status, :rera_number, :short_desc, :full_desc, :featured)
             RETURNING id'
        );
        $stmt->execute([
            'title' => $title,
            'type' => $type,
            'location' => trim($_POST['location'] ?? '') ?: null,
            'price' => trim($_POST['price'] ?? '') ?: null,
            'size' => trim($_POST['size'] ?? '') ?: null,
            'status' => $status,
            'rera_number' => trim($_POST['rera_number'] ?? '') ?: null,
            'short_desc' => trim($_POST['short_desc'] ?? '') ?: null,
            'full_desc' => trim($_POST['full_desc'] ?? '') ?: null,
            'featured' => isset($_POST['featured']) ? 1 : 0,
        ]);
        $projectId = (int) $stmt->fetchColumn();

        // Images
        if (!empty($_FILES['images']['name'][0])) {
            $count = count($_FILES['images']['name']);
            $sortOrder = 0;
            for ($i = 0; $i < $count; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                $file = [
                    'name' => $_FILES['images']['name'][$i],
                    'type' => $_FILES['images']['type'][$i],
                    'tmp_name' => $_FILES['images']['tmp_name'][$i],
                    'error' => $_FILES['images']['error'][$i],
                    'size' => $_FILES['images']['size'][$i],
                ];
                $imgError = null;
                $path = process_uploaded_image($file, $projectId, $imgError);
                if ($path) {
                    $imgStmt = $pdo->prepare('INSERT INTO project_images (project_id, image_path, sort_order) VALUES (:pid, :path, :sort)');
                    $imgStmt->execute(['pid' => $projectId, 'path' => $path, 'sort' => $sortOrder++]);
                } elseif ($imgError) {
                    $errors[] = $imgError;
                }
            }
        }

        // Brochure
        if (!empty($_FILES['brochure']['name'])) {
            $pdfError = null;
            $brochurePath = process_uploaded_pdf($_FILES['brochure'], $projectId, $pdfError);
            if ($brochurePath) {
                $updateStmt = $pdo->prepare('UPDATE projects SET brochure_path = :path WHERE id = :id');
                $updateStmt->execute(['path' => $brochurePath, 'id' => $projectId]);
            } elseif ($pdfError) {
                $errors[] = $pdfError;
            }
        }

        if (!empty($errors)) {
            // Project + valid fields/files were already saved; only some
            // file(s) failed. Surface that on the edit page instead of
            // silently dropping it.
            $_SESSION['flash_errors'] = $errors;
            header('Location: /admin/edit-project.php?id=' . $projectId);
            exit;
        }

        header('Location: /admin/dashboard.php?added=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Add Project | Roop Shree Construction Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
  <header class="admin-topbar">
    <div class="container admin-topbar__inner">
      <div class="logo">
        <img src="/assets/logo/logo.png" alt="Roop Shree Construction" class="logo__image">
      </div>
      <div class="admin-topbar__user">
        <a href="/admin/dashboard.php" class="link-underline">Back to Dashboard</a>
        <a href="/admin/logout.php" class="btn btn-outline">Log Out</a>
      </div>
    </div>
  </header>
  <main class="admin-main">
    <div class="container" style="max-width: 720px;">
      <h1 style="margin-bottom: 24px;">Add Project</h1>

      <?php if (!empty($errors)): ?>
        <div class="form-status form-status--error">
          <?php foreach ($errors as $err): ?>
            <div><?= htmlspecialchars($err) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" novalidate>
        <?php require __DIR__ . '/includes/project-fields.php'; ?>
        <button type="submit" class="btn btn-primary">Save Project</button>
      </form>
    </div>
  </main>
</div>
</body>
</html>
