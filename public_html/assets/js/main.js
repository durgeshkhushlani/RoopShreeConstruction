document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('primaryNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var isOpen = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  var filterBar = document.getElementById('filterBar');
  var propertyGrid = document.getElementById('propertyGrid');
  var filterEmptyState = document.getElementById('filterEmptyState');
  if (filterBar && propertyGrid) {
    filterBar.addEventListener('click', function (e) {
      var btn = e.target.closest('.filter-btn');
      if (!btn) return;

      filterBar.querySelectorAll('.filter-btn').forEach(function (b) {
        b.classList.remove('is-active');
      });
      btn.classList.add('is-active');

      var filter = btn.dataset.filter;
      var cards = propertyGrid.querySelectorAll('.property-card');
      var visibleCount = 0;

      cards.forEach(function (card) {
        var match = filter === 'All' || card.dataset.type === filter;
        card.style.display = match ? '' : 'none';
        if (match) visibleCount++;
      });

      if (filterEmptyState) {
        filterEmptyState.style.display = visibleCount === 0 ? '' : 'none';
      }
    });
  }

  var galleryThumbs = document.querySelectorAll('.property-gallery__thumb');
  var galleryMainImage = document.getElementById('galleryMainImage');
  if (galleryThumbs.length && galleryMainImage) {
    galleryThumbs.forEach(function (thumb) {
      thumb.addEventListener('click', function () {
        galleryMainImage.src = thumb.dataset.src;
        galleryThumbs.forEach(function (t) { t.classList.remove('is-active'); });
        thumb.classList.add('is-active');
      });
    });
  }

  var contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      var name = contactForm.querySelector('[name="name"]');
      var email = contactForm.querySelector('[name="email"]');
      var phone = contactForm.querySelector('[name="phone"]');
      var message = contactForm.querySelector('[name="message"]');
      var valid = true;

      [name, email, phone, message].forEach(function (field) {
        var errorEl = field.parentElement.querySelector('.form-error');
        if (!field.value.trim()) {
          valid = false;
          if (errorEl) errorEl.textContent = 'This field is required.';
        } else if (errorEl) {
          errorEl.textContent = '';
        }
      });

      var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (email.value.trim() && !emailPattern.test(email.value.trim())) {
        valid = false;
        var emailError = email.parentElement.querySelector('.form-error');
        if (emailError) emailError.textContent = 'Enter a valid email address.';
      }

      if (!valid) e.preventDefault();
    });
  }
});
