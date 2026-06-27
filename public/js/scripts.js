tailwind.config = {
    theme: {
        extend: {
            colors: {
                ink:     '#0A0704',
                dark:    '#110D08',
                surface: '#1A120A',
                card:    '#221608',
                amber:   '#C8783A',
                gold:    '#D4A017',
                cream:   '#F0E6D0',
                muted:   '#7A6550',
                border:  '#2E1F10',
            },
            fontFamily: {
                display: ['"Cormorant Garant"', 'Georgia', 'serif'],
                body:    ['"Outfit"', 'sans-serif'],
            },
        }
    }
}

/* ── Navbar al hacer scroll ──────────────────────────────── */
const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 55);
    }, { passive: true });
}

/* ── Menú mobile hamburger ───────────────────────────────── */
const hamBtn   = document.getElementById('ham-btn');
const mobMenu  = document.getElementById('mob-menu');
const hamOpen  = document.getElementById('ham-open');
const hamClose = document.getElementById('ham-close');

if (hamBtn && mobMenu) {
    hamBtn.addEventListener('click', () => {
        const isOpen = !mobMenu.classList.contains('hidden');
        mobMenu.classList.toggle('hidden');
        if(hamOpen) hamOpen.classList.toggle('hidden',  !isOpen);
        if(hamClose) hamClose.classList.toggle('hidden',  isOpen);
        hamBtn.setAttribute('aria-expanded', String(!isOpen));
    });

    /* Cerrar al hacer clic en un link del menú mobile */
    mobMenu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            mobMenu.classList.add('hidden');
            if(hamOpen) hamOpen.classList.remove('hidden');
            if(hamClose) hamClose.classList.add('hidden');
            hamBtn.setAttribute('aria-expanded', 'false');
        });
    });
}

/* ── Reveal al hacer scroll (Intersection Observer) ─────── */
if (typeof IntersectionObserver !== 'undefined') {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('show');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
}
