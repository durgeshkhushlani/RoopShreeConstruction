<?php
require __DIR__ . '/includes/auth-check.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/upload-helper.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: /admin/dashboard.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id');
$stmt->execute(['id' => $id]);
$existing = $stmt->fetch();

if (!$existing) {
    header('Location: /admin/dashboard.php');
    exit;
}

$errors = [];
if (!empty($_SESSION['flash_errors'])) {
    $errors = $_SESSION['flash_errors'];
    unset($_SESSION['flash_errors']);
}

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
        $updateStmt = $pdo->prepare(
            'UPDATE projects SET title = :title, type = :type, location = :location, price = :price,
             size = :size, status = :status, rera_number = :rera_number, short_desc = :short_desc,
             full_desc = :full_desc, featured = :featured WHERE id = :id'
        );
        $updateStmt->execute([
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
            'id' => $id,
        ]);

        // Remove selected existing images
        if (!empty($_POST['remove_images']) && is_array($_POST['remove_images'])) {
            $removeIds = array_map('intval', $_POST['remove_images']);
            $placeholders = implode(',', array_fill(0, count($removeIds), '?'));
            $imgStmt = $pdo->prepare("SELECT * FROM project_images WHERE id IN ($placeholders) AND project_id = ?");
            $imgStmt->execute([...$removeIds, $id]);
            foreach ($imgStmt->fetchAll() as $img) {
                delete_single_file($img['image_path']);
            }
            $delStmt = $pdo->prepare("DELETE FROM project_images WHERE id IN ($placeholders) AND project_id = ?");
            $delStmt->execute([...$removeIds, $id]);
        }

        // New images
        if (!empty($_FILES['images']['name'][0])) {
            $sortStmt = $pdo->prepare('SELECT COALESCE(MAX(sort_order), -1) FROM project_images WHERE project_id = :id');
            $sortStmt->execute(['id' => $id]);
            $maxSort = (int) $sortStmt->fetchColumn();
            $sortOrder = $maxSort + 1;
            $count = count($_FILES['images']['name']);
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
                $path = process_uploaded_image($file, $id, $imgError);
                if ($path) {
                    $insStmt = $pdo->prepare('INSERT INTO project_images (project_id, image_path, sort_order) VALUES (:pid, :path, :sort)');
                    $insStmt->execute(['pid' => $id, 'path' => $path, 'sort' => $sortOrder++]);
                } elseif ($imgError) {
                    $errors[] = $imgError;
                }
            }
        }

        // Brochure remove/replace
        if (!empty($_POST['remove_brochure']) && empty($_FILES['brochure']['name'])) {
            delete_single_file($existing['brochure_path']);
            $pdo->prepare('UPDATE projects SET brochure_path = NULL WHERE id = :id')->execute(['id' => $id]);
        }
        if (!empty($_FILES['brochure']['name'])) {
            $pdfError = null;
            $brochurePath = process_uploaded_pdf($_FILES['brochure'], $id, $pdfError);
            if ($brochurePath) {
                delete_single_file($existing['brochure_path']);
                $pdo->prepare('UPDATE projects SET brochure_path = :path WHERE id = :id')->execute(['path' => $brochurePath, 'id' => $id]);
            } elseif ($pdfError) {
                $errors[] = $pdfError;
            }
        }

        if (empty($errors)) {
            header('Location: /admin/dashboard.php?updated=1');
            exit;
        }
    }
}

// Reload current state for the form (post-update or on initial GET)
$stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id');
$stmt->execute(['id' => $id]);
$project = $stmt->fetch();

$imgStmt = $pdo->prepare('SELECT * FROM project_images WHERE project_id = :id ORDER BY sort_order');
$imgStmt->execute(['id' => $id]);
$existingImages = $imgStmt->fetchAll();
$currentBrochurePath = $project['brochure_path'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Edit Project | Roop Shree Construction Admin</title>
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
      <h1 style="margin-bottom: 24px;">Edit Project</h1>

      <?php if (!empty($errors)): ?>
        <div class="form-status form-status--error">
          <?php foreach ($errors as $err): ?>
            <div><?= htmlspecialchars($err) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" novalidate>
        <?php require __DIR__ . '/includes/project-fields.php'; ?>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </form>
    </div>
  </main>
</div>
</body>
</html>
