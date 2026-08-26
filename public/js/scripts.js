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

/* ════════════════════════════════════════════════════════════
   LOGIN MODULE — Toggle Mostrar / Ocultar Contraseña
   ════════════════════════════════════════════════════════════ */
const LoginModule = (() => {
    function init() {
        const toggleBtn = document.getElementById('toggle-password');
        const passInput = document.getElementById('login-password');
        const eyeIcon   = document.getElementById('toggle-password-icon');

        if (toggleBtn && passInput && eyeIcon) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passInput.getAttribute('type') === 'password';
                passInput.setAttribute('type', isPassword ? 'text' : 'password');

                eyeIcon.classList.toggle('fa-eye', !isPassword);
                eyeIcon.classList.toggle('fa-eye-slash', isPassword);
            });
        }
    }

    return { init };
})();

/* ════════════════════════════════════════════════════════════
   RESERVA MODULE — Maqueta 3D Interactiva y Gestión de Reservas
   ════════════════════════════════════════════════════════════ */
const ReservaModule = (() => {
    function init() {
        const mesh         = document.getElementById('floorplan-mesh');
        const camBtns      = document.querySelectorAll('.cam-btn');
        const tables       = document.querySelectorAll('.table-3d:not(.occupied)');
        const allTables    = document.querySelectorAll('.table-3d');
        const zoneCards    = document.querySelectorAll('.zona-card');
        const zonePerims   = document.querySelectorAll('.zone-perimeter');
        const timeBtns     = document.querySelectorAll('.time-slot-btn');
        const timeInput    = document.getElementById('reserva-hora');
        const tableInput   = document.getElementById('reserva-mesa-id');
        const tableTxt     = document.getElementById('reserva-mesa-nombre');
        const zoneInput    = document.getElementById('reserva-zona-id');
        const zoneTxt      = document.getElementById('reserva-zona-nombre');
        const summaryBadge = document.getElementById('reserva-selection-badge');
        const form         = document.getElementById('reserva-form');
        const formCard     = document.getElementById('reserva-form-card');
        const successCard  = document.getElementById('reserva-success-card');

        if (!form && !mesh) return;

        /* ── 1. Controles de Cámara 3D ────────────────────────── */
        camBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const view = btn.dataset.view;
                camBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                if (mesh) {
                    mesh.classList.remove('view-iso', 'view-top', 'view-front', 'view-3d-straight', 'view-2d-top', 'view-front-straight');
                    mesh.classList.add(`view-${view}`);
                }
            });
        });


        /* ── 2. Selección de Mesa en la Maqueta 3D ─────────────── */
        function selectTable(tableEl) {
            if (!tableEl || tableEl.classList.contains('occupied')) return;

            allTables.forEach(t => t.classList.remove('selected'));
            tableEl.classList.add('selected');

            const tableId   = tableEl.dataset.tableId   || '';
            const tableName = tableEl.dataset.tableName || '';
            const zoneId    = tableEl.dataset.zone      || '';
            const zoneName  = tableEl.dataset.zoneName  || '';

            if (tableInput) tableInput.value = tableId;
            if (tableTxt)   tableTxt.value   = tableName;
            if (zoneInput)  zoneInput.value  = zoneId;
            if (zoneTxt)    zoneTxt.value    = zoneName;

            if (summaryBadge) {
                summaryBadge.innerHTML = `<i class="fa-solid fa-check-circle mr-1 text-amber"></i> ${tableName} · ${zoneName}`;
                summaryBadge.classList.remove('hidden');
            }

            // Resalta el perímetro de la zona
            zonePerims.forEach(zp => {
                zp.classList.toggle('active-zone', zp.dataset.zone === zoneId);
            });

            // Resalta la tarjeta de zona correspondiente
            zoneCards.forEach(zc => {
                zc.classList.toggle('active', zc.dataset.zone === zoneId);
            });
        }

        tables.forEach(table => {
            table.addEventListener('click', (e) => {
                e.stopPropagation();
                selectTable(table);
            });
        });

        // Permitir clic en el conjunto completo de mesa + sillas
        document.querySelectorAll('.table-set-wrapper').forEach(wrapper => {
            wrapper.addEventListener('click', () => {
                const table = wrapper.querySelector('.table-3d:not(.occupied)');
                if (table) selectTable(table);
            });
        });




        /* ── 3. Selección desde Tarjetas de Zonas ──────────────── */
        zoneCards.forEach(card => {
            card.addEventListener('click', () => {
                const zone = card.dataset.zone;
                zoneCards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');

                // Busca la primera mesa disponible en esa zona
                const firstAvailable = document.querySelector(`.table-3d[data-zone="${zone}"]:not(.occupied)`);
                if (firstAvailable) {
                    selectTable(firstAvailable);
                }
            });
        });

        /* ── 4. Selección de Turnos de Horario ─────────────────── */
        timeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                timeBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                if (timeInput) timeInput.value = btn.dataset.time;
            });
        });

        /* ── 5. Contador de Comensales ────────────────────────── */
        const guestsDec = document.getElementById('guests-dec');
        const guestsInc = document.getElementById('guests-inc');
        const guestsVal = document.getElementById('guests-count');
        const guestsIn  = document.getElementById('reserva-personas');

        if (guestsDec && guestsInc && guestsVal && guestsIn) {
            guestsDec.addEventListener('click', () => {
                let current = parseInt(guestsIn.value, 10) || 2;
                if (current > 1) {
                    current--;
                    guestsIn.value = current;
                    guestsVal.textContent = current + (current === 1 ? ' persona' : ' personas');
                }
            });

            guestsInc.addEventListener('click', () => {
                let current = parseInt(guestsIn.value, 10) || 2;
                if (current < 12) {
                    current++;
                    guestsIn.value = current;
                    guestsVal.textContent = current + ' personas';
                }
            });
        }

        /* ── 6. Envío del Formulario y Generación de Ticket ─────── */
        if (form && successCard && formCard) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();

                const nombre   = document.getElementById('reserva-nombre')?.value || 'Invitado';
                const fecha    = document.getElementById('reserva-fecha')?.value || '';
                const hora     = timeInput?.value || '05:00 PM';
                const personas = guestsIn?.value || '2';
                const mesa     = tableTxt?.value || 'Mesa Asignada';
                const zona     = zoneTxt?.value || 'Salón';
                const randomId = Math.floor(1000 + Math.random() * 9000);
                const code     = `#RG-${randomId}`;

                // Formatea la fecha
                let formattedDate = fecha;
                if (fecha) {
                    const parts = fecha.split('-');
                    if (parts.length === 3) {
                        formattedDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                    }
                }

                // Inyecta en el ticket voucher
                const vCode   = document.getElementById('voucher-code');
                const vName   = document.getElementById('voucher-name');
                const vDate   = document.getElementById('voucher-date');
                const vTime   = document.getElementById('voucher-time');
                const vGuests = document.getElementById('voucher-guests');
                const vTable  = document.getElementById('voucher-table');
                const vZone   = document.getElementById('voucher-zone');
                const vWs     = document.getElementById('voucher-whatsapp-btn');

                if (vCode)   vCode.textContent   = code;
                if (vName)   vName.textContent   = nombre;
                if (vDate)   vDate.textContent   = formattedDate;
                if (vTime)   vTime.textContent   = hora;
                if (vGuests) vGuests.textContent = `${personas} personas`;
                if (vTable)  vTable.textContent  = mesa;
                if (vZone)   vZone.textContent   = zona;

                if (vWs) {
                    const msg = encodeURIComponent(`Hola Raíz & Grano, tengo mi reserva ${code} a nombre de ${nombre} para el ${formattedDate} a las ${hora} (${personas} personas en ${mesa} - ${zona}).`);
                    vWs.href = `https://wa.me/51999999999?text=${msg}`;
                }

                formCard.classList.add('hidden');
                successCard.classList.remove('hidden');

                successCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        }

        // Selección por defecto de la primera mesa disponible
        const initialTable = document.querySelector('.table-3d[data-table-id="S1"]');
        if (initialTable) selectTable(initialTable);
    }

    return { init };
})();

document.addEventListener('DOMContentLoaded', () => {
    GaleriaModule.init();
    ContactoModule.init();
    LoginModule.init();
    ReservaModule.init();
});





