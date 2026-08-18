<?php
require __DIR__ . '/includes/db.php';

$pageTitle = 'Properties';
$pageDescription = 'Browse property listings from Roop Shree Construction, Jodhpur.';

$stmt = $pdo->prepare(
    "SELECT p.*, (SELECT image_path FROM project_images WHERE project_id = p.id ORDER BY sort_order LIMIT 1) AS cover_image
     FROM projects p ORDER BY created_at DESC"
);
$stmt->execute();
$projects = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="hero hero--simple">
  <div class="container hero__inner">
    <h1>Our Properties</h1>
    <p>Flats, plots, villas, and commercial spaces across Jodhpur.</p>
  </div>
</section>

<section>
  <div class="container">
    <?php if (empty($projects)): ?>
      <div class="empty-state">
        <p>No properties listed yet. Check back soon.</p>
      </div>
    <?php else: ?>
      <div class="filter-bar" id="filterBar">
        <button type="button" class="filter-btn is-active" data-filter="All">All</button>
        <button type="button" class="filter-btn" data-filter="Flat">Flat</button>
        <button type="button" class="filter-btn" data-filter="Plot">Plot</button>
        <button type="button" class="filter-btn" data-filter="Villa">Villa</button>
        <button type="button" class="filter-btn" data-filter="Commercial">Commercial</button>
      </div>

      <div class="property-grid" id="propertyGrid">
        <?php foreach ($projects as $project): ?>
          <a href="/property.php?id=<?= urlencode($project['id']) ?>" class="property-card" data-type="<?= htmlspecialchars($project['type']) ?>">
            <div class="property-card__image">
              <?php if ($project['cover_image']): ?>
                <img src="<?= htmlspecialchars($project['cover_image']) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
              <?php else: ?>
                <span>No image yet</span>
              <?php endif; ?>
            </div>
            <div class="property-card__body">
              <div class="property-card__badges">
                <span class="badge badge--type"><?= htmlspecialchars($project['type']) ?></span>
                <span class="badge badge--status"><?= htmlspecialchars($project['status']) ?></span>
              </div>
              <h3 class="property-card__title"><?= htmlspecialchars($project['title']) ?></h3>
              <p class="property-card__location"><?= htmlspecialchars($project['location'] ?? '') ?></p>
              <p class="property-card__price"><?= htmlspecialchars($project['price'] ?? '') ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="empty-state" id="filterEmptyState" style="display:none;">
        <p>No properties match this filter yet.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
