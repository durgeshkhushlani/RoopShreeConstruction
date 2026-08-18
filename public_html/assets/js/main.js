document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('primaryNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var isOpen = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
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
