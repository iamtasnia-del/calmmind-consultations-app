// =========================
// Mobile navigation toggle
// =========================
const navToggle = document.querySelector('[data-nav-toggle]');
const mainNav = document.querySelector('[data-main-nav]');

if (navToggle && mainNav) {
    navToggle.addEventListener('click', () => {
        const isOpen = mainNav.classList.toggle('is-open');
        navToggle.setAttribute('aria-expanded', String(isOpen));
    });
}

// =========================
// Lightbox for media items
// =========================
const lightboxBackdrop = document.querySelector('[data-lightbox]');
const lightboxImage = document.querySelector('[data-lightbox-img]');
const lightboxCaption = document.querySelector('[data-lightbox-caption]');
const lightboxClose = document.querySelector('[data-lightbox-close]');

function isSafeMediaSrc(src) {
    if (!src) {
        return false;
    }
    try {
        const url = new URL(src, window.location.origin);
        return url.protocol === 'http:' || url.protocol === 'https:';
    } catch (e) {
        return false;
    }
}

if (lightboxBackdrop && lightboxImage && lightboxCaption) {
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-media-trigger]');
        if (trigger) {
            const largeSrc = trigger.getAttribute('data-media-src');
            const description = trigger.getAttribute('data-media-description') || '';
            if (!isSafeMediaSrc(largeSrc)) {
                return;
            }
            lightboxImage.src = largeSrc;
            lightboxImage.alt = description;
            lightboxCaption.textContent = description;
            lightboxBackdrop.setAttribute('aria-hidden', 'false');
            lightboxClose.focus();
        }
    });

    lightboxClose.addEventListener('click', () => {
        lightboxBackdrop.setAttribute('aria-hidden', 'true');
        lightboxImage.src = '';
        lightboxCaption.textContent = '';
    });

    lightboxBackdrop.addEventListener('click', (e) => {
        if (e.target === lightboxBackdrop) {
            lightboxBackdrop.setAttribute('aria-hidden', 'true');
            lightboxImage.src = '';
            lightboxCaption.textContent = '';
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && lightboxBackdrop.getAttribute('aria-hidden') === 'false') {
            lightboxBackdrop.setAttribute('aria-hidden', 'true');
            lightboxImage.src = '';
            lightboxCaption.textContent = '';
        }
    });
}

// =========================
// Toggle password visibility
// =========================
document.addEventListener('click', (e) => {
    const toggleBtn = e.target.closest('.toggle-password');
    if (toggleBtn) {
        const targetSelector = toggleBtn.getAttribute('data-toggle');
        const passwordInput = document.querySelector(targetSelector);
        if (passwordInput) {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleBtn.textContent = isPassword ? 'Hide' : 'Show';
        }
    }
});

// =========================
// Contact form validation
// =========================
const contactForm = document.querySelector('#contactForm');

if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const fields = {
            fullName: contactForm.querySelector('#fullName'),
            email: contactForm.querySelector('#email'),
            message: contactForm.querySelector('#message'),
            topic: contactForm.querySelector('#topic')
        };

        const feedback = contactForm.querySelector('[data-form-feedback]');
        let firstErrorField = null;
        let hasError = false;

        // Helper to show error
        const showError = (field, message) => {
            const errorSpan = contactForm.querySelector(`#${field.id}Error`);
            field.setAttribute('aria-invalid', 'true');
            if (errorSpan) {
                errorSpan.textContent = message;
                errorSpan.style.display = 'block';
            }
            if (!firstErrorField) {
                firstErrorField = field;
            }
            hasError = true;
        };

        // Reset previous errors
        Object.values(fields).forEach((field) => {
            field.setAttribute('aria-invalid', 'false');
            const errorSpan = contactForm.querySelector(`#${field.id}Error`);
            if (errorSpan) {
                errorSpan.textContent = '';
                errorSpan.style.display = 'none';
            }
        });
        feedback.textContent = '';
        feedback.className = 'feedback';

        // Basic validation
        if (!fields.fullName.value.trim()) {
            showError(fields.fullName, 'Please enter your full name.');
        }

        const emailValue = fields.email.value.trim();
        if (!emailValue) {
            showError(fields.email, 'Please enter your email address.');
        } else {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailValue)) {
                showError(fields.email, 'Please enter a valid email address.');
            }
        }

        if (!fields.topic.value) {
            showError(fields.topic, 'Please choose a consultation topic.');
        }

        if (!fields.message.value.trim()) {
            showError(fields.message, 'Please provide a brief summary of your concern.');
        }

        if (hasError) {
            feedback.textContent = 'Please fix the highlighted fields and try again.';
            feedback.classList.add('feedback--error');
            if (firstErrorField) {
                firstErrorField.focus();
            }
            return;
        }

        // Simulated success (no real backend)
        contactForm.reset();
        feedback.textContent = 'Thank you for reaching out. A CalmMind coordinator will contact you within one working day.';
        feedback.classList.add('feedback--success');
    });
}
