<?php
http_response_code(404);
$pageTitle = 'Page Not Found';
$pageDescription = 'The page you are looking for could not be found.';
require __DIR__ . '/includes/header.php';
?>

<section>
  <div class="container" style="text-align:center; padding-block: 60px;">
    <h1>Page Not Found</h1>
    <p style="margin: 16px 0 32px;">The page you're looking for doesn't exist or may have moved.</p>
    <a href="/index.php" class="btn btn-primary">Back to Home</a>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
