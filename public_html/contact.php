<?php
$pageTitle = 'Contact';
$pageDescription = 'Get in touch with Roop Shree Construction, Jodhpur.';
require __DIR__ . '/includes/header.php';

$status = $_GET['status'] ?? null;
?>

<section class="hero hero--simple">
  <div class="container hero__inner">
    <h1>Get in Touch</h1>
    <p>Have a question about one of our projects? Send us a message and we'll get back to you.</p>
  </div>
</section>

<section>
  <div class="container contact-layout">
    <div class="contact-info">
      <h2>Contact Us</h2>
      <div class="contact-info__item">
        <h3>Phone</h3>
        <p><a href="tel:+919782310601">+91 9782 310 601</a></p>
        <p><a href="tel:+919351574127">+91 9351 574 127</a></p>
      </div>
      <div class="contact-info__item">
        <h3>Email</h3>
        <p><a href="mailto:info@roopshreeconstruction.com">info@roopshreeconstruction.com</a></p>
      </div>
      <div class="contact-info__item">
        <h3>Visit Us</h3>
        <p>Flat No. 102, Jeet Apartment,<br>Near HDFC Bank, Ratanada, Jodhpur</p>
      </div>
    </div>

    <div class="contact-form-wrap">
      <?php if ($status === 'success'): ?>
        <div class="form-status form-status--success">Thanks for reaching out — we'll get back to you shortly.</div>
      <?php elseif ($status === 'error'): ?>
        <div class="form-status form-status--error">Something went wrong sending your message. Please try again or email us directly.</div>
      <?php endif; ?>

      <form id="contactForm" action="/contact-handler.php" method="POST" novalidate>
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name">
          <div class="form-error"></div>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email">
          <div class="form-error"></div>
        </div>
        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="tel" id="phone" name="phone" required>
          <div class="form-error"></div>
        </div>
        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" rows="5"><?= isset($_GET['project']) ? 'Enquiry about: ' . htmlspecialchars($_GET['project']) . "\n\n" : '' ?></textarea>
          <div class="form-error"></div>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
      </form>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
