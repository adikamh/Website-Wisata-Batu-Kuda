import '../css/app.css';

// Alpine.js or vanilla JS interactions
document.addEventListener('DOMContentLoaded', () => {
    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar?.classList.add('scrolled');
        } else {
            navbar?.classList.remove('scrolled');
        }
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