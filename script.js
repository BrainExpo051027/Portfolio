// Theme persistence
const THEME_KEY = 'prefers-theme';
const root = document.documentElement;
const themeToggle = document.getElementById('themeToggle');

function setTheme(mode) {
  if (mode === 'light') {
    root.classList.add('light');
  } else {
    root.classList.remove('light');
  }
  localStorage.setItem(THEME_KEY, mode);
}

function initTheme() {
  const stored = localStorage.getItem(THEME_KEY);
  if (stored) {
    setTheme(stored);
    return;
  }
  const prefersLight = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches;
  setTheme(prefersLight ? 'light' : 'dark');
}

initTheme();

if (themeToggle) {
  themeToggle.addEventListener('click', () => {
    const isLight = root.classList.toggle('light');
    localStorage.setItem(THEME_KEY, isLight ? 'light' : 'dark');
  });
}

// Mobile nav toggle
const navToggle = document.querySelector('.nav-toggle');
const navLinks = document.querySelector('.nav-links');
if (navToggle && navLinks) {
  navToggle.addEventListener('click', () => {
    const opened = navLinks.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', String(opened));
  });
  navLinks.addEventListener('click', (e) => {
    if (e.target.closest('a')) {
      navLinks.classList.remove('open');
      navToggle.setAttribute('aria-expanded', 'false');
    }
  });
}

// Scroll animations
function initScrollAnimations() {
  const animatedElements = document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right');
  
  const animationObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-in');
        // Unobserve after animation to prevent re-triggering
        animationObserver.unobserve(entry.target);
      }
    });
  }, { 
    rootMargin: '-10% 0px -10% 0px', 
    threshold: 0.1 
  });

  animatedElements.forEach(element => {
    animationObserver.observe(element);
  });

  // Special observer for hero image with different timing
  const heroImage = document.getElementById('heroImage');
  if (heroImage) {
    const heroObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            entry.target.classList.add('animate-in');
          }, 200); // Small delay for dramatic effect
          heroObserver.unobserve(entry.target);
        }
      });
    }, { 
      rootMargin: '0px 0px -20% 0px', 
      threshold: 0.3 
    });

    heroObserver.observe(heroImage);
  }

  // Enhanced background names slide-in animation
  const backgroundNames = document.querySelectorAll('.background-name');
  if (backgroundNames.length > 0) {
    const backgroundObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          // Create wave effect with different timing for each row
          const topRow = [0, 1, 2, 3, 4, 5];
          const leftSide = [6, 7, 8, 9];
          const rightSide = [10, 11, 12, 13];
          const bottomRow = [14, 15, 16, 17, 18, 19];
          
          // Animate top row first
          topRow.forEach((index, i) => {
            setTimeout(() => {
              backgroundNames[index].classList.add('slide-in');
            }, i * 120);
          });
          
          // Animate left and right sides simultaneously
          setTimeout(() => {
            leftSide.forEach((index, i) => {
              setTimeout(() => {
                backgroundNames[index].classList.add('slide-in');
              }, i * 150);
            });
            rightSide.forEach((index, i) => {
              setTimeout(() => {
                backgroundNames[index].classList.add('slide-in');
              }, i * 150);
            });
          }, 300);
          
          // Animate bottom row last
          setTimeout(() => {
            bottomRow.forEach((index, i) => {
              setTimeout(() => {
                backgroundNames[index].classList.add('slide-in');
              }, i * 120);
            });
          }, 600);
          
          backgroundObserver.unobserve(entry.target);
        }
      });
    }, { 
      rootMargin: '0px 0px -10% 0px', 
      threshold: 0.15 
    });

    // Observe the hero image section to trigger background names animations
    const heroSection = document.getElementById('hero-image');
    if (heroSection) {
      backgroundObserver.observe(heroSection);
    }
  }

  // Profile image switching on hover
  const profileContainer = document.getElementById('profileContainer');
  const heroImage1 = document.getElementById('heroImage1');
  const heroImage2 = document.getElementById('heroImage2');
  
  if (profileContainer && heroImage1 && heroImage2) {
    let isHovering = false;
    let switchTimeout;
    
    profileContainer.addEventListener('mouseenter', () => {
      isHovering = true;
      clearTimeout(switchTimeout);
      
      // Switch to second image
      heroImage1.classList.remove('active');
      heroImage2.classList.add('active');
    });
    
    profileContainer.addEventListener('mouseleave', () => {
      isHovering = false;
      
      // Switch back to first image after a short delay
      switchTimeout = setTimeout(() => {
        if (!isHovering) {
          heroImage2.classList.remove('active');
          heroImage1.classList.add('active');
        }
      }, 300);
    });
  }
}

// Initialize scroll animations
initScrollAnimations();

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      const headerHeight = document.querySelector('.site-header').offsetHeight;
      const targetPosition = target.offsetTop - headerHeight - 20;
      
      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });
    }
  });
});

// Active link highlighting on scroll
const sections = document.querySelectorAll('main section[id]');
const navAnchors = document.querySelectorAll('.nav-links a');
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    const id = entry.target.getAttribute('id');
    const link = document.querySelector(`.nav-links a[href="#${id}"]`);
    if (!link) return;
    if (entry.isIntersecting) {
      navAnchors.forEach(a => a.classList.remove('is-active'));
      link.classList.add('is-active');
    }
  });
}, { rootMargin: '-50% 0px -40% 0px', threshold: 0.01 });

sections.forEach(section => observer.observe(section));

// Current year in footer
const year = document.getElementById('year');
if (year) year.textContent = String(new Date().getFullYear());

// Appointment form handling
const appointmentForm = document.getElementById('appointmentForm');
const statusEl = document.getElementById('appointmentStatus');
const addToGoogle = document.getElementById('addToGoogle');

function toGoogleCalendarUrl({ title, details, location, start, end }) {
  const params = new URLSearchParams({
    action: 'TEMPLATE',
    text: title,
    details,
    location,
    dates: `${start}/${end}`,
  });
  return `https://calendar.google.com/calendar/render?${params.toString()}`;
}

function formatDateTimeForCalendar(dateStr, timeStr) {
  // Input: yyyy-mm-dd and HH:MM (24h)
  const [y, m, d] = dateStr.split('-').map(Number);
  const [hh, mm] = timeStr.split(':').map(Number);
  const start = new Date(Date.UTC(y, m - 1, d, hh, mm));
  const end = new Date(start.getTime() + 60 * 60 * 1000); // 1-hour default
  const fmt = (dt) => dt.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
  return { start: fmt(start), end: fmt(end) };
}

if (appointmentForm) {
  appointmentForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    statusEl.textContent = 'Sending...';
    const submitBtn = document.getElementById('submitAppointment');
    if (submitBtn) submitBtn.disabled = true;
    const fd = new FormData(appointmentForm);
    try {
      const res = await fetch('appointment.php', {
        method: 'POST',
        body: fd,
        headers: { 'Accept': 'application/json' },
      });
      let data;
      try {
        data = await res.json();
      } catch (_) {
        const text = await res.text();
        throw new Error(text || 'Unexpected server response');
      }
      if (!res.ok || !data.ok) throw new Error(data.message || 'Request failed');
      statusEl.textContent = 'Request sent. Please check your email for confirmation.';
      appointmentForm.reset();
    } catch (err) {
      statusEl.textContent = (err && err.message) ? `Error: ${err.message}` : 'Sorry, there was a problem sending your request.';
    }
    if (submitBtn) submitBtn.disabled = false;
  });
}

if (addToGoogle) {
  addToGoogle.addEventListener('click', () => {
    const name = document.getElementById('name')?.value || '';
    const email = document.getElementById('email')?.value || '';
    const date = document.getElementById('date')?.value || '';
    const time = document.getElementById('time')?.value || '';
    const message = document.getElementById('message')?.value || '';
    if (!date || !time) {
      if (statusEl) statusEl.textContent = 'Select a date and time first.';
      return;
    }
    const { start, end } = formatDateTimeForCalendar(date, time);
    const url = toGoogleCalendarUrl({
      title: 'Appointment with Joshua',
      details: `${name} (${email})\n${message}`,
      location: 'Online',
      start,
      end,
    });
    window.open(url, '_blank', 'noopener');
  });
}

// View-all toggles for lists (experience, projects, certificates)
function initViewAll({ containerSelector, itemSelector, initiallyVisible = 3, label = 'View all', labelCollapse = 'Show less' }) {
  const container = document.querySelector(containerSelector);
  if (!container) return;
  const items = Array.from(container.querySelectorAll(itemSelector));
  if (items.length <= initiallyVisible) return;

  // Mark extras
  items.forEach((el, idx) => {
    if (idx >= initiallyVisible) el.setAttribute('data-extra', '');
  });
  container.classList.add('is-collapsed');

  // Controls
  const controls = document.createElement('div');
  controls.className = 'view-all-controls';
  const btn = document.createElement('button');
  btn.type = 'button';
  btn.className = 'view-all-button';
  btn.textContent = `${label} (${items.length})`;
  btn.setAttribute('aria-expanded', 'false');
  controls.appendChild(btn);

  // Insert after container
  container.parentNode.insertBefore(controls, container.nextSibling);

  btn.addEventListener('click', () => {
    const collapsed = container.classList.toggle('is-collapsed');
    const expanded = !collapsed;
    btn.setAttribute('aria-expanded', String(expanded));
    btn.textContent = expanded ? labelCollapse : `${label} (${items.length})`;
  });
}

// Initialize view-all on sections
document.addEventListener('DOMContentLoaded', () => {
  // Experience items are articles under .experience-list
  initViewAll({ containerSelector: '#experience-list', itemSelector: '.experience-item', initiallyVisible: 2 });
  // Project cards
  initViewAll({ containerSelector: '#projects-grid', itemSelector: '.project-card', initiallyVisible: 3 });
  // Certificates
  initViewAll({ containerSelector: '#certificates-list', itemSelector: '.certificate', initiallyVisible: 3 });
});


