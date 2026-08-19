<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/includes/db.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: /admin/dashboard.php');
    exit;
}

$error = null;
$maxAttempts = 5;
$lockoutSeconds = 300;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
    $_SESSION['login_locked_until'] = $_SESSION['login_locked_until'] ?? 0;

    if (time() < $_SESSION['login_locked_until']) {
        $error = 'Too many failed attempts. Please try again in a few minutes.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare('SELECT * FROM admin_users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['login_attempts'] = 0;
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            header('Location: /admin/dashboard.php');
            exit;
        }

        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] >= $maxAttempts) {
            $_SESSION['login_locked_until'] = time() + $lockoutSeconds;
            $error = 'Too many failed attempts. Please try again in a few minutes.';
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Admin Login | Roop Shree Construction</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
<div class="admin-login__card">
  <div class="logo" style="justify-content:center; margin-bottom:28px;">
    <img src="/assets/logo/logo.png" alt="Roop Shree Construction" class="logo__image">
  </div>
  <h1 style="text-align:center; font-size:1.3rem; margin-bottom:24px;">Admin Login</h1>

  <?php if ($error): ?>
    <div class="form-status form-status--error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" novalidate>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required autofocus>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;">Log In</button>
  </form>
</div>
</body>
</html>
