/* ============================================================
   SD BIOSENSOR PANAMÁ — JavaScript principal (Vanilla JS)
   ============================================================ */

import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    /* --------------------------------------------------------
       1. Navbar: transparente → blanca al hacer scroll > 80px
       -------------------------------------------------------- */
    const navbar = document.getElementById('navbar');
    if (navbar) {
        const handleNavScroll = () => {
            navbar.classList.toggle('scrolled', window.scrollY > 80);
        };
        handleNavScroll();
        window.addEventListener('scroll', handleNavScroll);
    }

    /* --------------------------------------------------------
       2. Carrusel del hero: autoplay 5s + controles manuales
       -------------------------------------------------------- */
    const slides   = Array.from(document.querySelectorAll('.hero-slide'));
    const dots     = Array.from(document.querySelectorAll('.hero-dot'));
    const prevBtn  = document.getElementById('hero-prev');
    const nextBtn  = document.getElementById('hero-next');

    if (slides.length > 0) {
        let current = 0;
        let timer   = null;

        const goToSlide = (index) => {
            current = (index + slides.length) % slides.length;
            slides.forEach((s, i) => s.classList.toggle('active', i === current));
            dots.forEach((d, i) => d.classList.toggle('active', i === current));
        };

        const next = () => goToSlide(current + 1);
        const prev = () => goToSlide(current - 1);

        const startAutoplay = () => {
            stopAutoplay();
            timer = setInterval(next, 5000);
        };
        const stopAutoplay = () => {
            if (timer) clearInterval(timer);
        };

        if (nextBtn) nextBtn.addEventListener('click', () => { next(); startAutoplay(); });
        if (prevBtn) prevBtn.addEventListener('click', () => { prev(); startAutoplay(); });

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                goToSlide(parseInt(dot.dataset.slide, 10));
                startAutoplay();
            });
        });

        // Pausar al pasar el cursor por el hero
        const hero = document.getElementById('hero');
        if (hero) {
            hero.addEventListener('mouseenter', stopAutoplay);
            hero.addEventListener('mouseleave', startAutoplay);
        }

        startAutoplay();
    }

    /* --------------------------------------------------------
       3. Scroll reveal con IntersectionObserver
       -------------------------------------------------------- */
    const revealEls = document.querySelectorAll('.reveal');
    if (revealEls.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        revealEls.forEach((el) => observer.observe(el));
    }

    /* --------------------------------------------------------
       4. Botón "volver arriba": visible tras scroll > 300px
       -------------------------------------------------------- */
    const scrollTopBtn = document.getElementById('scroll-top');
    if (scrollTopBtn) {
        window.addEventListener('scroll', () => {
            scrollTopBtn.classList.toggle('visible', window.scrollY > 300);
        });
        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* --------------------------------------------------------
       5. Menú flotante FAB (+)
       -------------------------------------------------------- */
    const fabBtn  = document.getElementById('fab-btn');
    const fabMenu = document.getElementById('fab-menu');
    if (fabBtn && fabMenu) {
        fabBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            fabMenu.classList.toggle('open');
            fabBtn.classList.toggle('rotated');
        });
        // Cerrar al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!fabMenu.contains(e.target) && !fabBtn.contains(e.target)) {
                fabMenu.classList.remove('open');
                fabBtn.classList.remove('rotated');
            }
        });
    }

    /* --------------------------------------------------------
       6. Menú hamburguesa (mobile)
       -------------------------------------------------------- */
    const hamburger = document.getElementById('hamburger');
    const navMenu   = document.getElementById('nav-menu');
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('open');
            const icon = hamburger.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
        });

        // Cerrar el menú al hacer clic en un enlace
        navMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('open');
                const icon = hamburger.querySelector('i');
                if (icon) {
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            });
        });
    }

});
