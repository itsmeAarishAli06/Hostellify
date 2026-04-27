/* ================================================================
   HostelHub — Complete Fixed JavaScript
   ================================================================ */

/* ── Set Active Navigation Link (FIXED VERSION) ── */
function setActiveNavLink() {
    // Get current page filename
    let currentPage = window.location.pathname.split('/').pop();
    
    // If empty (root page), default to index.php
    if (!currentPage || currentPage === '') {
        currentPage = 'index.php';
    }
    
    // Remove query parameters if any (e.g., "about.php?id=1" -> "about.php")
    currentPage = currentPage.split('?')[0];
    
    // Debug: Log to console
    console.log('Navigation Debug - Current Page:', currentPage);
    
    // Select ALL nav links (desktop + mobile)
    const navLinks = document.querySelectorAll('.nav-link');
    
    console.log('Navigation Debug - Found Links:', navLinks.length);
    
    // If no links found, log warning
    if (navLinks.length === 0) {
        console.warn('Warning: No .nav-link elements found!');
        return;
    }
    
    // Loop through each link
    navLinks.forEach(link => {
        // Get the href attribute
        const href = link.getAttribute('href');
        
        // Remove active class from all first
        link.classList.remove('active');
        
        // Add active class to matching link
        if (href && href === currentPage) {
            link.classList.add('active');
            console.log('Active class added to:', href);
        }
    });
}

// Call the function at multiple points for maximum reliability
// 1. Immediately when script loads (synchronous)
if (document.readyState === 'loading') {
    // Document still loading
    console.log('Script loaded - DOM still loading');
} else {
    // Document already loaded (rare but possible)
    console.log('Script loaded - DOM already ready');
    setActiveNavLink();
}

// 2. When DOM content is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoaded fired');
    setActiveNavLink();
});

// 3. When everything is fully loaded (images, styles, etc)
window.addEventListener('load', () => {
    console.log('Window load fired');
    setActiveNavLink();
});

// 4. Also run on page show (important for back button)
window.addEventListener('pageshow', () => {
    console.log('Page show fired (back button)');
    setActiveNavLink();
});

/* ── Particles ── */
function createParticles() {
    const container = document.getElementById('particlesContainer');
    for (let i = 0; i < 25; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left            = Math.random() * 100 + '%';
        particle.style.animationDelay    = Math.random() * 20 + 's';
        particle.style.animationDuration = (15 + Math.random() * 10) + 's';
        particle.style.width             = (2 + Math.random() * 3) + 'px';
        particle.style.height            = particle.style.width;
        container.appendChild(particle);
    }
}
createParticles();

/* ── Hamburger / Mobile Nav ── */
const hamburgerMenu = document.getElementById('hamburgerMenu');
const mobileNav     = document.getElementById('mobileNav');

hamburgerMenu.addEventListener('click', () => {
    hamburgerMenu.classList.toggle('active');
    mobileNav.classList.toggle('active');
});

const mobileNavLinks = mobileNav.querySelectorAll('.nav-link');
mobileNavLinks.forEach(link => {
    link.addEventListener('click', () => {
        hamburgerMenu.classList.remove('active');
        mobileNav.classList.remove('active');
    });
});

document.addEventListener('click', (event) => {
    const isClickInsideNav  = mobileNav.contains(event.target);
    const isClickOnHamburger = hamburgerMenu.contains(event.target);
    if (!isClickInsideNav && !isClickOnHamburger && mobileNav.classList.contains('active')) {
        hamburgerMenu.classList.remove('active');
        mobileNav.classList.remove('active');
    }
});

/* ── Section Navigation ── */
function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(s  => s.classList.remove('active'));
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));

    const section = document.getElementById(sectionId);
    if (section) {
        section.classList.add('active');
    }

    if (event && event.target) {
        event.target.classList.add('active');
    }

    hamburgerMenu.classList.remove('active');
    mobileNav.classList.remove('active');

    /* Scroll to top of section smoothly */
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ── Dashboard Tabs ── */
function showTab(tabId) {
    document.querySelectorAll('.dashboard-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b         => b.classList.remove('active'));

    const content = document.getElementById(tabId);
    if (content) {
        content.classList.add('active');
    }

    if (event && event.target) {
        event.target.classList.add('active');
    }
}


/* ── Social Icons (footer) ── */
// document.querySelectorAll('.social-icon').forEach(icon => {
//     icon.addEventListener('click', function () {
//         const platform = this.getAttribute('title');
//         alert(`Opening ${platform}…`);
//     });
// });

/* ================================================================
   COUNTER ANIMATION (About page stats)
   ================================================================ */
function animateCounter(element) {
    const target = parseInt(element.getAttribute('data-count'));
    const duration = 2000;
    const increment = target / (duration / 16);
    let current = 0;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}

// Trigger counter animations when About section is visible
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
            const counters = entry.target.querySelectorAll('.ab-count');
            counters.forEach(counter => animateCounter(counter));
            entry.target.classList.add('animated');
        }
    });
});

document.querySelectorAll('.ab-mini-stats, .dark-banner').forEach(el => {
    observer.observe(el);
});

/* ================================================================
   CONTACT FORM
   ================================================================ */
const contactForm      = document.getElementById('contactForm');
const contactSuccess   = document.getElementById('contactSuccess');
const contactResetBtn  = document.getElementById('contactResetBtn');
const contactSubmitBtn = document.getElementById('contactSubmitBtn');

if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const name    = document.getElementById('c_name').value.trim();
        const email   = document.getElementById('c_email').value.trim();
        const message = document.getElementById('c_message').value.trim();

        let ok = true;
        if (!name)                                           { flashError('c_name');    ok = false; }
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { flashError('c_email');   ok = false; }
        if (!message)                                        { flashError('c_message'); ok = false; }

        if (!ok) {
            /* Shake the form box */
            contactForm.style.animation = 'none';
            void contactForm.offsetWidth;
            contactForm.style.animation = 'shake .4s ease';
            return;
        }

        /* Loading state */
        contactSubmitBtn.disabled = true;
        contactSubmitBtn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                 style="animation:spin .7s linear infinite;flex-shrink:0">
                <polyline points="23 4 23 10 17 10"/>
                <path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/>
            </svg>&nbsp;Sending…`;

        setTimeout(() => {
            contactForm.style.display = 'none';
            contactSuccess.classList.add('show');
        }, 1400);
    });
}

if (contactResetBtn) {
    contactResetBtn.addEventListener('click', function () {
        contactSuccess.classList.remove('show');
        contactForm.style.display = 'block';
        contactForm.reset();
        contactSubmitBtn.disabled = false;
        contactSubmitBtn.innerHTML = `
            <span class="contact-btn-text">Send Message</span>
            <span class="contact-btn-icon"><i class="fas fa-paper-plane"></i></span>`;
    });
}

function flashError(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.borderColor = '#ef4444';
    el.style.boxShadow   = '0 0 0 3px rgba(239,68,68,0.15)';
    el.addEventListener('input', () => {
        el.style.borderColor = '';
        el.style.boxShadow   = '';
    }, { once: true });
}

/* ================================================================
   FAQ ACCORDION
   ================================================================ */
function toggleFaq(btn) {
    const item   = btn.parentElement;
    const answer = item.querySelector('.faq-a');
    const isOpen = btn.classList.contains('active');

    /* Close all open answers */
    document.querySelectorAll('.faq-q.active').forEach(q => {
        q.classList.remove('active');
        q.parentElement.querySelector('.faq-a').classList.remove('open');
    });
    if (!isOpen) {
        btn.classList.add('active');
        answer.classList.add('open');
    }
}

/* ================================================================
   INJECTED KEYFRAMES
   ================================================================ */
const extraKf = document.createElement('style');
extraKf.textContent = `
    @keyframes shake {
        0%,100% { transform: translateX(0); }
        20%,60%  { transform: translateX(-8px); }
        40%,80%  { transform: translateX(8px); }
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(extraKf);
/* ================================================================
   PROFILE DROPDOWN JAVASCRIPT
   ================================================================*/
document.addEventListener('DOMContentLoaded', function() {
    
    // Get Elements
    const profileAvatarBtn = document.getElementById('profileAvatarBtn');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');
    
    // Check if profile elements exist
    if (!profileAvatarBtn || !profileDropdownMenu) {
        return; // User not logged in, no need for dropdown
    }

    // Toggle Dropdown on Avatar Click
    profileAvatarBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        profileDropdownMenu.classList.toggle('show');
    });

    // Close Dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.profile-dropdown-wrapper')) {
            profileDropdownMenu.classList.remove('show');
        }
    });

    // Close Dropdown when clicking on a link inside
    const logoutBtn = profileDropdownMenu.querySelector('.profile-logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            profileDropdownMenu.classList.remove('show');
            // Page will redirect to logout.php
        });
    }

    // Close Dropdown on Escape Key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            profileDropdownMenu.classList.remove('show');
        }
    });

});

/* ================================================================
   END OF PROFILE DROPDOWN JAVASCRIPT
   ================================================================ */
// DASHBOARD PROFILE UPDATE 

    document.querySelector('form[action="../Backend/backend.php"]').addEventListener('submit', function(e) {
        const name     = this.querySelector('input[name="name"]').value.trim();
        const email    = this.querySelector('input[name="email"]').value.trim();
        const password = this.querySelector('input[name="password"]').value.trim();
        const contact  = this.querySelector('input[name="contact"]').value.trim();
        const address  = this.querySelector('textarea[name="address"]').value.trim();

        if (!name) {
            alert('Full name is required.'); e.preventDefault(); return;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('Enter a valid email address.'); e.preventDefault(); return;
        }
        if (password.length < 6) {
            alert('Password must be at least 6 characters.'); e.preventDefault(); return;
        }
        if (!/^\+?[0-9]{10,14}$/.test(contact)) {
            alert('Enter a valid contact number (11-14 digits, + allowed).'); e.preventDefault(); return;
        }
        if (!address) {
            alert('Address is required.'); e.preventDefault(); return;
        }
    });
function updatePrice(price, radioEl) {
    document.getElementById('display-price').textContent = 'Rs ' + price;
    document.getElementById('selected-price').value = price;
    document.getElementById('selected-room-type').value = radioEl.value;
}
