<?php
require __DIR__ . '/includes/auth-check.php';
require __DIR__ . '/includes/db.php';

$stmt = $pdo->query('SELECT * FROM projects ORDER BY created_at DESC');
$projects = $stmt->fetchAll();

$notice = null;
if (isset($_GET['added'])) {
    $notice = 'Project added.';
} elseif (isset($_GET['updated'])) {
    $notice = 'Project updated.';
} elseif (isset($_GET['deleted'])) {
    $notice = 'Project deleted.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Dashboard | Roop Shree Construction Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
  <header class="admin-topbar">
    <div class="container admin-topbar__inner">
      <div class="logo">
        <span class="logo__mark" aria-hidden="true">RS</span>
        <span class="logo__text">Roop Shree<br><small>Construction Admin</small></span>
      </div>
      <div class="admin-topbar__user">
        <span>Signed in as <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
        <a href="/admin/logout.php" class="btn btn-outline">Log Out</a>
      </div>
    </div>
  </header>
  <main class="admin-main">
    <div class="container">
      <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
        <h1>Projects</h1>
        <a href="/admin/add-project.php" class="btn btn-primary">Add Project</a>
      </div>

      <?php if ($notice): ?>
        <div class="form-status form-status--success"><?= htmlspecialchars($notice) ?></div>
      <?php endif; ?>

      <?php if (empty($projects)): ?>
        <div class="empty-state">
          <p>No projects yet. Click "Add Project" to create your first listing.</p>
        </div>
      <?php else: ?>
        <div style="overflow-x:auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Status</th>
                <th>Featured</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($projects as $proj): ?>
                <tr>
                  <td><?= htmlspecialchars($proj['title']) ?></td>
                  <td><?= htmlspecialchars($proj['type']) ?></td>
                  <td><?= htmlspecialchars($proj['status']) ?></td>
                  <td><?= $proj['featured'] ? 'Yes' : 'No' ?></td>
                  <td>
                    <div class="admin-actions">
                      <a href="/admin/edit-project.php?id=<?= $proj['id'] ?>" class="btn btn-outline">Edit</a>
                      <form method="POST" action="/admin/delete-project.php" onsubmit="return confirm('Delete this project? This also removes its images and brochure permanently.');" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $proj['id'] ?>">
                        <button type="submit" class="btn btn-outline" style="border-color:#B03A2E; color:#B03A2E;">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </main>
</div>
</body>
</html>
