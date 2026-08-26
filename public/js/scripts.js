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

/* ════════════════════════════════════════════════════════════
   GALERIA MODULE — Filtros y Lightbox Modal
   ════════════════════════════════════════════════════════════ */
const GaleriaModule = (() => {
    let currentIndex = 0;
    let visibleCards = [];

    function init() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const cards = Array.from(document.querySelectorAll('.galeria-card'));
        const modal = document.getElementById('lightbox-modal');

        if (!cards.length && !filterBtns.length) return;

        visibleCards = cards;

        /* ── Filtrado por categoría ───────────────────────────── */
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.filter;

                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                visibleCards = [];

                cards.forEach(card => {
                    const category = card.dataset.category;
                    const match = target === 'todos' || category === target;

                    if (match) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        }, 20);
                        visibleCards.push(card);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 250);
                    }
                });
            });
        });

        /* ── Lightbox Modal ───────────────────────────────────── */
        if (!modal) return;

        const imgEl     = document.getElementById('lightbox-img');
        const titleEl   = document.getElementById('lightbox-title');
        const descEl    = document.getElementById('lightbox-desc');
        const badgeEl   = document.getElementById('lightbox-badge');
        const counterEl = document.getElementById('lightbox-counter');
        const closeBtn  = document.getElementById('lightbox-close');
        const prevBtn   = document.getElementById('lightbox-prev');
        const nextBtn   = document.getElementById('lightbox-next');

        function updateLightbox(index) {
            if (!visibleCards.length) return;
            if (index < 0) index = visibleCards.length - 1;
            if (index >= visibleCards.length) index = 0;

            currentIndex = index;
            const currentCard = visibleCards[currentIndex];

            const img   = currentCard.dataset.img || '';
            const title = currentCard.dataset.title || '';
            const desc  = currentCard.dataset.desc || '';
            const badge = currentCard.dataset.badge || '';

            if (imgEl)   imgEl.src = img;
            if (titleEl) titleEl.textContent = title;
            if (descEl)  descEl.textContent = desc;
            if (badgeEl) {
                badgeEl.textContent = badge;
                badgeEl.style.display = badge ? 'inline-block' : 'none';
            }
            if (counterEl) {
                counterEl.textContent = `${currentIndex + 1} / ${visibleCards.length}`;
            }
        }

        function openLightbox(card) {
            const idx = visibleCards.indexOf(card);
            if (idx === -1) return;
            updateLightbox(idx);
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        function prevImage() {
            updateLightbox(currentIndex - 1);
        }

        function nextImage() {
            updateLightbox(currentIndex + 1);
        }

        cards.forEach(card => {
            card.addEventListener('click', () => openLightbox(card));
        });

        if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
        if (prevBtn)  prevBtn.addEventListener('click', e => { e.stopPropagation(); prevImage(); });
        if (nextBtn)  nextBtn.addEventListener('click', e => { e.stopPropagation(); nextImage(); });

        modal.addEventListener('click', e => {
            if (e.target === modal) closeLightbox();
        });

        document.addEventListener('keydown', e => {
            if (!modal.classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
        });
    }

    return { init };
})();

/* ════════════════════════════════════════════════════════════
   CONTACTO MODULE — Acordeón FAQ y Envío de Formulario
   ════════════════════════════════════════════════════════════ */
const ContactoModule = (() => {
    function init() {
        /* ── Acordeón de FAQs ─────────────────────────────────── */
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            const trigger = item.querySelector('.faq-trigger');
            if (!trigger) return;

            trigger.addEventListener('click', () => {
                const isActive = item.classList.contains('active');

                /* Cerrar otros acordeones abiertos si se desea comportamiento exclusivo */
                faqItems.forEach(other => {
                    if (other !== item) other.classList.remove('active');
                });

                item.classList.toggle('active', !isActive);
            });
        });

        /* ── Feedback de Formulario de Contacto ──────────────── */
        const contactForm = document.getElementById('contacto-form');
        const formSuccess = document.getElementById('contacto-success');

        if (contactForm && formSuccess) {
            contactForm.addEventListener('submit', e => {
                e.preventDefault();
                contactForm.classList.add('hidden');
                formSuccess.classList.remove('hidden');
            });
        }
    }

    return { init };
})();

document.addEventListener('DOMContentLoaded', () => {
    GaleriaModule.init();
    ContactoModule.init();
});



