import '../css/app.css';

// Alpine.js or vanilla JS interactions
document.addEventListener('DOMContentLoaded', () => {
    const cookieConsent = document.getElementById('cookieConsent');
    const consentCookieName = 'batu_kuda_cookie_consent';
    const consentMaxAge = 60 * 60 * 24 * 180;

    const getCookie = (name) => {
        const prefix = `${name}=`;
        return document.cookie
            .split(';')
            .map((item) => item.trim())
            .find((item) => item.startsWith(prefix))
            ?.slice(prefix.length) ?? null;
    };

    const setCookie = (name, value, maxAge) => {
        document.cookie = `${name}=${value}; path=/; max-age=${maxAge}; SameSite=Lax`;
    };

    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    const navToggle = document.getElementById('navToggle');
    const navShell = document.getElementById('navLinks');
    const userDropdownButton = document.getElementById('userDropdownButton');
    const userDropdown = document.getElementById('userDropdown');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar?.classList.add('scrolled');
        } else {
            navbar?.classList.remove('scrolled');
        }
    });

    navToggle?.addEventListener('click', () => {
        const expanded = navToggle.getAttribute('aria-expanded') === 'true';
        navToggle.setAttribute('aria-expanded', String(!expanded));
        navShell?.classList.toggle('is-open', !expanded);
        document.body.classList.toggle('nav-open', !expanded);
    });

    userDropdownButton?.addEventListener('click', () => {
        const expanded = userDropdownButton.getAttribute('aria-expanded') === 'true';
        userDropdownButton.setAttribute('aria-expanded', String(!expanded));
        userDropdown?.classList.toggle('is-open', !expanded);
    });

    document.addEventListener('click', (event) => {
        if (userDropdown && userDropdownButton && !event.target.closest('.nav-user')) {
            userDropdown.classList.remove('is-open');
            userDropdownButton.setAttribute('aria-expanded', 'false');
        }
    });

    document.querySelectorAll('a.is-home-link[href^="#"]').forEach((link) => {
        link.addEventListener('click', (event) => {
            const href = link.getAttribute('href');
            const target = href ? document.querySelector(href) : null;

            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            navShell?.classList.remove('is-open');
            navToggle?.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('nav-open');
        });
    });

    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('[data-nav-link][data-section]');

    if (sections.length > 0 && navLinks.length > 0) {
        const setActiveLink = () => {
            const scrollPosition = window.scrollY + 140;
            let activeSection = '';

            sections.forEach((section) => {
                if (scrollPosition >= section.offsetTop && scrollPosition < section.offsetTop + section.offsetHeight) {
                    activeSection = section.id;
                }
            });

            navLinks.forEach((link) => {
                link.classList.toggle('is-active', link.dataset.section === activeSection);
            });
        };

        setActiveLink();
        window.addEventListener('scroll', setActiveLink);
    }

    document.querySelectorAll('.nav-links a').forEach((link) => {
        link.addEventListener('click', () => {
            navShell?.classList.remove('is-open');
            navToggle?.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('nav-open');
        });
    });

    if (cookieConsent && !getCookie(consentCookieName)) {
        cookieConsent.hidden = false;
    }

    cookieConsent?.querySelectorAll('[data-cookie-consent]').forEach((button) => {
        button.addEventListener('click', () => {
            setCookie(consentCookieName, button.dataset.cookieConsent, consentMaxAge);
            cookieConsent.hidden = true;
        });
    });

    // Fade-in on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

    // Parallax hero
    const hero = document.querySelector('.hero-bg');
    window.addEventListener('scroll', () => {
        if (hero) {
            const offset = window.scrollY * 0.4;
            hero.style.transform = `translateY(${offset}px)`;
        }
    });

    // Counter animation
    document.querySelectorAll('.counter').forEach(counter => {
        const target = +counter.dataset.target;
        const duration = 1800;
        const step = target / (duration / 16);
        let current = 0;
        const update = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(update);
            } else {
                counter.textContent = target.toLocaleString();
            }
        };
        const obs = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting) { update(); obs.disconnect(); }
        });
        obs.observe(counter);
    });
});
