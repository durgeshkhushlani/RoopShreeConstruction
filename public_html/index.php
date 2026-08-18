<?php
require __DIR__ . '/includes/db.php';

$pageTitle = 'Home';
$pageDescription = 'Roop Shree Construction — trusted real estate developer in Jodhpur, building flats, plots, villas, and commercial spaces.';

$stmt = $pdo->prepare(
    "SELECT p.*, (SELECT image_path FROM project_images WHERE project_id = p.id ORDER BY sort_order LIMIT 1) AS cover_image
     FROM projects p WHERE featured = TRUE ORDER BY created_at DESC LIMIT 6"
);
$stmt->execute();
$featuredProjects = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="hero hero--split">
  <div class="hero__grid">
    <div class="hero__text">
      <h1>Building Trusted Homes &amp; Spaces in Jodhpur</h1>
      <p>Roop Shree Construction delivers flats, plots, villas, and commercial properties designed for lasting value.</p>
      <div class="hero-actions">
        <a href="/properties.php" class="btn btn-primary">View Properties</a>
        <a href="/contact.php" class="btn btn-outline">Enquire Now</a>
      </div>
    </div>
    <div class="hero__image">
      <img src="/assets/images/hero.jpg" alt="Mehrangarh Fort overlooking Jodhpur at sunset">
    </div>
  </div>
</section>

<section class="featured">
  <div class="container">
    <div class="section-heading">
      <h2>Featured Projects</h2>
      <p>A selection of our current developments across Jodhpur.</p>
    </div>

    <?php if (empty($featuredProjects)): ?>
      <div class="empty-state">
        <p>Featured projects will appear here soon. Check back shortly.</p>
      </div>
    <?php else: ?>
      <div class="property-grid">
        <?php foreach ($featuredProjects as $project): ?>
          <a href="/property.php?id=<?= urlencode($project['id']) ?>" class="property-card">
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
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
