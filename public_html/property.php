<?php
require __DIR__ . '/includes/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$project = null;
$images = [];

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $project = $stmt->fetch();

    if ($project) {
        $imgStmt = $pdo->prepare('SELECT * FROM project_images WHERE project_id = :id ORDER BY sort_order');
        $imgStmt->execute(['id' => $id]);
        $images = $imgStmt->fetchAll();
    }
}

$pageTitle = $project ? $project['title'] : 'Property Not Found';
$pageDescription = $project ? ($project['short_desc'] ?? $project['title']) : 'This property could not be found.';
require __DIR__ . '/includes/header.php';
?>

<?php if (!$project): ?>
  <section>
    <div class="container">
      <div class="empty-state">
        <p>This property couldn't be found. <a href="/properties.php" class="link-underline">Browse all properties</a>.</p>
      </div>
    </div>
  </section>
<?php else: ?>

  <section style="padding-top: 40px;">
    <div class="container">
      <div class="property-gallery">
        <div class="property-gallery__main" id="galleryMain">
          <?php if (!empty($images)): ?>
            <img src="<?= htmlspecialchars($images[0]['image_path']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" id="galleryMainImage">
          <?php else: ?>
            <span>No images uploaded yet</span>
          <?php endif; ?>
        </div>
        <?php if (count($images) > 1): ?>
          <div class="property-gallery__thumbs">
            <?php foreach ($images as $i => $img): ?>
              <button type="button" class="property-gallery__thumb <?= $i === 0 ? 'is-active' : '' ?>" data-src="<?= htmlspecialchars($img['image_path']) ?>">
                <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="">
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section>
    <div class="container property-detail">
      <div class="property-detail__main">
        <div class="property-card__badges">
          <span class="badge badge--type"><?= htmlspecialchars($project['type']) ?></span>
          <span class="badge badge--status"><?= htmlspecialchars($project['status']) ?></span>
        </div>
        <h1><?= htmlspecialchars($project['title']) ?></h1>
        <p class="property-detail__location"><?= htmlspecialchars($project['location'] ?? '') ?></p>

        <?php if (!empty($project['full_desc'])): ?>
          <div class="property-detail__desc">
            <?= nl2br(htmlspecialchars($project['full_desc'])) ?>
          </div>
        <?php endif; ?>
      </div>

      <aside class="property-detail__sidebar">
        <div class="property-detail__card">
          <p class="property-detail__price"><?= htmlspecialchars($project['price'] ?? 'Price on request') ?></p>

          <dl class="property-detail__specs">
            <?php if (!empty($project['size'])): ?>
              <dt>Size</dt><dd><?= htmlspecialchars($project['size']) ?></dd>
            <?php endif; ?>
            <dt>Type</dt><dd><?= htmlspecialchars($project['type']) ?></dd>
            <dt>Status</dt><dd><?= htmlspecialchars($project['status']) ?></dd>
            <?php if (!empty($project['rera_number'])): ?>
              <dt>RERA No.</dt><dd><?= htmlspecialchars($project['rera_number']) ?></dd>
            <?php endif; ?>
          </dl>

          <a href="/contact.php?project=<?= urlencode($project['title']) ?>" class="btn btn-primary" style="width: 100%; text-align: center;">Enquire Now</a>

          <?php if (!empty($project['brochure_path'])): ?>
            <a href="<?= htmlspecialchars($project['brochure_path']) ?>" class="btn btn-outline" style="width: 100%; text-align: center; margin-top: 12px;" target="_blank" rel="noopener">Download Brochure</a>
          <?php endif; ?>
        </div>
      </aside>
    </div>
  </section>

<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
