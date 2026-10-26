/**
 * Akash Event Management - Form Enhancements & Client Validation
 */

document.addEventListener('DOMContentLoaded', () => {
  const enquiryForm = document.querySelector('.enquiry-form');
  if (!enquiryForm) return;

  enquiryForm.addEventListener('submit', (e) => {
    let isValid = true;
    const requiredInputs = enquiryForm.querySelectorAll('[required]');

    requiredInputs.forEach(input => {
      const group = input.closest('.form-group');
      let errorEl = group ? group.querySelector('.client-error') : null;

      if (!input.value.trim()) {
        isValid = false;
        if (!errorEl && group) {
          errorEl = document.createElement('div');
          errorEl.className = 'form-error client-error';
          errorEl.textContent = 'This field is required.';
          group.appendChild(errorEl);
        }
      } else {
        if (errorEl) errorEl.remove();
      }
    });

    // Validate phone number
    const phoneInput = enquiryForm.querySelector('input[name="phone"]');
    if (phoneInput && phoneInput.value.trim()) {
      const cleanPhone = phoneInput.value.replace(/[^\d+]/g, '');
      const group = phoneInput.closest('.form-group');
      let errorEl = group ? group.querySelector('.client-phone-error') : null;

      if (cleanPhone.length < 10) {
        isValid = false;
        if (!errorEl && group) {
          errorEl = document.createElement('div');
          errorEl.className = 'form-error client-phone-error';
          errorEl.textContent = 'Please enter a valid phone number (at least 10 digits).';
          group.appendChild(errorEl);
        }
      } else {
        if (errorEl) errorEl.remove();
      }
    }

    if (!isValid) {
      e.preventDefault();
      return;
    }

    // Prevent double submission
    const submitBtn = enquiryForm.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = `
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin-icon" style="animation: spin 1s linear infinite;">
          <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
          <path d="M12 2a10 10 0 0 1 10 10"></path>
        </svg>
        Sending Enquiry...
      `;
    }
  });
});
