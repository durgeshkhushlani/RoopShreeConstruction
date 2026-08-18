<?php
$pageTitle = 'About Us';
$pageDescription = 'Learn about Roop Shree Construction, a trusted real estate developer based in Jodhpur.';
require __DIR__ . '/includes/header.php';
?>

<section class="hero hero--simple">
  <div class="container hero__inner">
    <h1>About Roop Shree Construction</h1>
    <p>A Jodhpur-based real estate developer committed to quality construction and honest dealing.</p>
  </div>
</section>

<section>
  <div class="container" style="max-width: 780px;">
    <h2>Our Story</h2>
    <p style="margin-top: 16px;">
      Roop Shree Construction has been building flats, plots, villas, and commercial spaces across Jodhpur,
      with a focus on transparent processes, quality materials, and timely delivery. Every project we take on
      is treated as a long-term commitment to the families and businesses who choose to build their future with us.
    </p>
    <p style="margin-top: 16px;">
      Our team oversees every stage of development — from land approval and RERA compliance to final handover —
      so buyers can invest with confidence.
    </p>

    <h2 style="margin-top: 48px;">Why Choose Us</h2>
    <div class="property-grid" style="margin-top: 24px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
      <div>
        <h3>RERA Compliant</h3>
        <p>All eligible projects are registered and compliant with RERA regulations.</p>
      </div>
      <div>
        <h3>Transparent Pricing</h3>
        <p>Clear, upfront pricing with no hidden costs.</p>
      </div>
      <div>
        <h3>Quality Construction</h3>
        <p>Trusted materials and experienced contractors on every site.</p>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
