</main>

<?php if (($currentPage ?? '') !== 'contact.php'): ?>
<a href="/contact.php" class="floating-contact" aria-label="Contact us">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
  </svg>
</a>
<?php endif; ?>

<footer class="site-footer">
  <div class="container site-footer__inner">
    <div class="site-footer__col">
      <div class="logo logo--footer">
        <span class="logo__mark" aria-hidden="true">RS</span>
        <span class="logo__text">Roop Shree<br><small>Construction</small></span>
      </div>
      <p class="site-footer__tagline">Building trusted homes and spaces in Jodhpur.</p>
    </div>

    <div class="site-footer__col">
      <h3>Explore</h3>
      <a href="/index.php">Home</a>
      <a href="/properties.php">Properties</a>
      <a href="/about.php">About</a>
      <a href="/contact.php">Contact</a>
    </div>

    <div class="site-footer__col">
      <h3>Contact</h3>
      <p>Jodhpur, Rajasthan, India</p>
      <p><a href="tel:+910000000000">+91 00000 00000</a></p>
      <p><a href="mailto:info@roopshreeconstruction.com">info@roopshreeconstruction.com</a></p>
    </div>

    <div class="site-footer__col">
      <h3>Follow</h3>
      <div class="social-icons">
        <a href="#" aria-label="Facebook">FB</a>
        <a href="#" aria-label="Instagram">IG</a>
        <a href="#" aria-label="WhatsApp">WA</a>
      </div>
    </div>
  </div>
  <div class="site-footer__bottom">
    <div class="container">
      <p>&copy; <?= date('Y') ?> Roop Shree Construction. All rights reserved.</p>
    </div>
  </div>
</footer>
<script src="/assets/js/main.js"></script>
</body>
</html>
