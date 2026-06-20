/* ============================================================
   SD BIOSENSOR PANAMÁ — JavaScript principal (Vanilla JS)
   ============================================================ */

import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    // En el home, el contenido se navega como full-page (una sección por
    // pantalla). En ese modo el navbar/scroll-top/reveal los gestiona el
    // controlador full-page, no los eventos de scroll de la ventana.
    const isFullpage = document.documentElement.classList.contains('snap-home');

    /* --------------------------------------------------------
       1. Navbar: transparente → blanca al hacer scroll > 80px
       -------------------------------------------------------- */
    const navbar = document.getElementById('navbar');
    if (navbar && !isFullpage) {
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
    if (revealEls.length > 0 && !isFullpage) {
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
    if (scrollTopBtn && !isFullpage) {
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

    /* --------------------------------------------------------
       7. Full-page por sección (solo home): una sección por
          pantalla, con salto + transición a la siguiente/anterior.
       -------------------------------------------------------- */
    if (isFullpage) initFullpage();
});

/* ============================================================
   Controlador full-page
   ============================================================ */
function initFullpage() {
    const main = document.getElementById('main-content');
    if (!main) return;

    const sections = Array.from(main.children).filter((el) => el.tagName === 'SECTION');
    if (sections.length < 2) return;

    // Mueve las secciones a un carril que se trasladará verticalmente.
    const track = document.createElement('div');
    track.className = 'fullpage-track';
    sections.forEach((s) => track.appendChild(s));
    main.appendChild(track);

    // Integra el footer como pie de la última sección (queda accesible).
    const footer = document.querySelector('body > .footer');
    if (footer) sections[sections.length - 1].appendChild(footer);

    const navbar = document.getElementById('navbar');
    const scrollTopBtn = document.getElementById('scroll-top');

    const TRANSITION_MS = 850;       // debe ser >= a la transición CSS (0.8s)
    const TOUCH_THRESHOLD = 40;      // px mínimos de swipe para saltar
    const lastIndex = sections.length - 1;

    let index = 0;
    let animating = false;

    const revealSection = (section) => {
        section.querySelectorAll('.reveal').forEach((el) => el.classList.add('visible'));
    };

    const render = () => {
        track.style.transform = `translateY(-${index * 100}vh)`;
        // Navbar transparente sobre el hero (índice 0), blanca en el resto.
        if (navbar) navbar.classList.toggle('scrolled', index > 0);
        if (scrollTopBtn) scrollTopBtn.classList.toggle('visible', index > 0);
        revealSection(sections[index]);
    };

    const goTo = (target) => {
        const clamped = Math.max(0, Math.min(lastIndex, target));
        if (clamped === index || animating) return;
        index = clamped;
        animating = true;
        render();
        window.setTimeout(() => { animating = false; }, TRANSITION_MS);
    };

    const next = () => goTo(index + 1);
    const prev = () => goTo(index - 1);

    // ¿La sección activa todavía puede scrollear internamente en esa dirección?
    // (Para secciones cuyo contenido excede la altura de la pantalla.)
    const canScrollInternally = (direction) => {
        const section = sections[index];
        const overflowing = section.scrollHeight > section.clientHeight + 1;
        if (!overflowing) return false;
        if (direction > 0) {
            return section.scrollTop + section.clientHeight < section.scrollHeight - 1;
        }
        return section.scrollTop > 1;
    };

    // Rueda del mouse / trackpad
    window.addEventListener('wheel', (e) => {
        const direction = e.deltaY > 0 ? 1 : -1;
        if (canScrollInternally(direction)) return;   // deja el scroll interno
        e.preventDefault();
        if (animating) return;
        direction > 0 ? next() : prev();
    }, { passive: false });

    // Teclado
    window.addEventListener('keydown', (e) => {
        if (['ArrowDown', 'PageDown'].includes(e.key)) {
            if (canScrollInternally(1)) return;
            e.preventDefault();
            next();
        } else if (['ArrowUp', 'PageUp'].includes(e.key)) {
            if (canScrollInternally(-1)) return;
            e.preventDefault();
            prev();
        } else if (e.key === 'Home') {
            e.preventDefault();
            goTo(0);
        } else if (e.key === 'End') {
            e.preventDefault();
            goTo(lastIndex);
        }
    });

    // Touch (swipe vertical)
    let touchStartY = null;
    window.addEventListener('touchstart', (e) => {
        touchStartY = e.touches[0].clientY;
    }, { passive: true });

    window.addEventListener('touchmove', (e) => {
        if (touchStartY === null) return;
        const delta = touchStartY - e.touches[0].clientY;
        const direction = delta > 0 ? 1 : -1;
        if (Math.abs(delta) < TOUCH_THRESHOLD) return;
        if (canScrollInternally(direction)) return;
        e.preventDefault();
        if (!animating) {
            direction > 0 ? next() : prev();
        }
        touchStartY = null;
    }, { passive: false });

    window.addEventListener('touchend', () => { touchStartY = null; });

    // El botón "volver arriba" salta a la primera sección.
    if (scrollTopBtn) {
        scrollTopBtn.addEventListener('click', (e) => {
            e.preventDefault();
            goTo(0);
        });
    }

    // Reaplica la posición al cambiar el tamaño (vh es relativo a la pantalla).
    window.addEventListener('resize', render);

    render();
}
