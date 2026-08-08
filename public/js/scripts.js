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
   CART MODULE — Carrito de compras con offcanvas lateral
   ════════════════════════════════════════════════════════════ */

const CartModule = (() => {

    /* ── Estado ──────────────────────────────────────────── */
    let items = [];

    /* Clave de localStorage — espacio de nombres del proyecto */
    const STORAGE_KEY = 'raiz_grano_cart';

    /* ── Referencias al DOM (se asignan en init) ──────────────── */
    let sidebar, overlay, emptyEl, listEl, footerEl, totalEl;

    /* ── Helpers ───────────────────────────────────────────── */

    /**
     * Extrae el valor numérico de un precio con formato "S/ 10"
     * @param {string} str
     * @returns {number}
     */
    function parsePrice(str) {
        return parseFloat(str.replace(/[^\d.]/g, '')) || 0;
    }

    /**
     * Formatea un número como precio en soles: "S/ 10.00"
     * @param {number} num
     * @returns {string}
     */
    function formatPrice(num) {
        return `S/ ${num.toFixed(2)}`;
    }

    /**
     * Escapa caracteres HTML para evitar XSS en el HTML generado por JS
     * @param {string} str
     * @returns {string}
     */
    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /* ── Persistencia en localStorage ────────────────────────── */

    /**
     * Guarda el estado actual del carrito en localStorage.
     * Se llama tras cada mutación del estado.
     */
    function saveCart() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        } catch (e) { /* cuota o modo privado: ignorar */ }
    }

    /**
     * Restaura el estado del carrito desde localStorage.
     * Se llama una vez al inicializar, antes de vincular eventos.
     */
    function loadCart() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (raw) items = JSON.parse(raw);
        } catch (e) {
            items = [];
        }
    }

    /* ── Mutaciones del estado ───────────────────────────────── */

    /**
     * Busca un ítem en el estado del carrito por nombre
     * @param {string} nombre
     * @returns {object|undefined}
     */
    function findItem(nombre) {
        return items.find(i => i.nombre === nombre);
    }

    /**
     * Agrega un producto al carrito o incrementa su cantidad.
     * NO abre el sidebar automáticamente.
     * Si el sidebar está abierto y el ítem ya existe, actualiza en sitio.
     * Si el ítem es nuevo y el sidebar está abierto, re-renderiza la lista.
     * @param {string} nombre
     * @param {string} precioStr  Precio con formato original (ej: "S/ 10")
     */
    function addItem(nombre, precioStr) {
        const existing = findItem(nombre);
        if (existing) {
            existing.qty += 1;
            saveCart();
            updateBadges();
            if (isSidebarOpen()) updateItemInPlace(nombre);
        } else {
            items.push({ nombre, precioStr, precio: parsePrice(precioStr), qty: 1 });
            saveCart();
            updateBadges();
            if (isSidebarOpen()) renderCartList();
        }
    }

    /**
     * Elimina completamente un producto del carrito
     * @param {string} nombre
     */
    function removeItem(nombre) {
        items = items.filter(i => i.nombre !== nombre);
        saveCart();
        updateBadges();
        renderCartList();
    }

    /**
     * Incrementa o decrementa la cantidad de un producto.
     * Actualiza el DOM en sitio sin re-renderizar toda la lista.
     * Si la cantidad llega a 0, elimina el producto.
     * @param {string} nombre
     * @param {number} delta  +1 o -1
     */
    function updateQty(nombre, delta) {
        const item = findItem(nombre);
        if (!item) return;
        item.qty += delta;
        if (item.qty <= 0) {
            removeItem(nombre); /* removeItem ya llama saveCart */
        } else {
            saveCart();
            updateBadges();
            updateItemInPlace(nombre);
        }
    }

    /* ── Render parcial ────────────────────────────────────── */

    /**
     * Actualiza el contador de cantidad y el subtotal de un ítem
     * directamente en el DOM, sin tocar el resto de la lista.
     * @param {string} nombre
     */
    function updateItemInPlace(nombre) {
        const item = findItem(nombre);
        if (!item || !listEl) return;

        const li = Array.from(listEl.querySelectorAll('.cart-item'))
            .find(el => el.dataset.nombre === nombre);
        if (!li) return;

        const qtyEl      = li.querySelector('.qty-count');
        const subtotalEl = li.querySelector('.cart-item-subtotal');

        if (qtyEl)      qtyEl.textContent     = item.qty;
        if (subtotalEl) subtotalEl.textContent = formatPrice(item.precio * item.qty);

        updateTotal();
    }

    /**
     * Recalcula y actualiza solo el total en el footer del carrito
     */
    function updateTotal() {
        if (!totalEl) return;
        const total = items.reduce((acc, i) => acc + i.precio * i.qty, 0);
        totalEl.textContent = formatPrice(total);
    }

    /* ── Render completo (solo cuando cambia nº de ítems) ─── */

    /**
     * Re-renderiza la lista completa del carrito.
     * Se llama cuando se agrega un ítem nuevo, se elimina uno, o se abre el sidebar.
     */
    function renderCartList() {
        if (!listEl) return;

        if (items.length === 0) {
            emptyEl.classList.remove('hidden');
            listEl.classList.add('hidden');
            footerEl.classList.add('hidden');
            return;
        }

        emptyEl.classList.add('hidden');
        listEl.classList.remove('hidden');
        footerEl.classList.remove('hidden');

        listEl.innerHTML = items.map(item => `
            <li class="cart-item" data-nombre="${escHtml(item.nombre)}">

                <span class="cart-item-name">${escHtml(item.nombre)}</span>

                <button
                    type="button"
                    class="cart-item-remove"
                    data-action="remove"
                    data-nombre="${escHtml(item.nombre)}"
                    aria-label="Eliminar ${escHtml(item.nombre)} del carrito"
                >
                    <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                </button>

                <div class="cart-item-controls">
                    <button
                        type="button"
                        class="qty-btn"
                        data-action="dec"
                        data-nombre="${escHtml(item.nombre)}"
                        aria-label="Reducir cantidad"
                    >
                        <i class="fa-solid fa-minus" aria-hidden="true"></i>
                    </button>

                    <span class="qty-count">${item.qty}</span>

                    <button
                        type="button"
                        class="qty-btn"
                        data-action="inc"
                        data-nombre="${escHtml(item.nombre)}"
                        aria-label="Aumentar cantidad"
                    >
                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    </button>

                    <span style="color:rgba(122,101,80,.7); font-size:.72rem; margin-left:.2rem">
                        ${escHtml(item.precioStr)} c/u
                    </span>
                </div>

                <span class="cart-item-subtotal">
                    ${formatPrice(item.precio * item.qty)}
                </span>

            </li>
        `).join('');

        updateTotal();
        attachItemEvents();
    }

    /* ── Badges ────────────────────────────────────────────── */

    /**
     * Actualiza el badge del icono del carrito en la navbar.
     * Solo muestra el total de unidades en el carrito.
     */
    function updateBadges() {
        const totalCount = items.reduce((acc, i) => acc + i.qty, 0);
        document.querySelectorAll('.cart-badge').forEach(badge => {
            badge.textContent = totalCount > 99 ? '99+' : totalCount;
            badge.classList.toggle('visible', totalCount > 0);
        });
    }

    /**
     * Delega los eventos de los botones dentro del listado del carrito
     */
    function attachItemEvents() {
        if (!listEl) return;
        listEl.querySelectorAll('[data-action]').forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.dataset.action;
                const nombre = btn.dataset.nombre;
                if (action === 'remove') removeItem(nombre);
                else if (action === 'inc')  updateQty(nombre,  1);
                else if (action === 'dec')  updateQty(nombre, -1);
            });
        });
    }

    /* ── Helper de estado del sidebar ──────────────────────── */

    function isSidebarOpen() {
        return sidebar && sidebar.classList.contains('open');
    }

    /* ── Apertura y cierre del panel ───────────────────────── */

    function openCart() {
        if (!sidebar) return;
        renderCartList();  /* Sincroniza el contenido al abrirlo */
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        sidebar.removeAttribute('aria-hidden');
    }

    function closeCart() {
        if (!sidebar) return;
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        sidebar.setAttribute('aria-hidden', 'true');
    }

    /* ── Inicialización ────────────────────────────────────── */

    function init() {
        sidebar  = document.getElementById('cart-sidebar');
        overlay  = document.getElementById('cart-overlay');
        emptyEl  = document.getElementById('cart-empty');
        listEl   = document.getElementById('cart-items-list');
        footerEl = document.getElementById('cart-footer');
        totalEl  = document.getElementById('cart-total');

        /* Restaurar carrito desde localStorage en cada carga de página */
        loadCart();
        updateBadges();

        /* El sidebar solo existe en la vista /carta */
        if (!sidebar) return;

        /* Botón de cierre dentro del panel */
        const closeBtn = document.getElementById('cart-close-btn');
        if (closeBtn) closeBtn.addEventListener('click', closeCart);

        /* Clic en el overlay cierra el carrito */
        overlay.addEventListener('click', closeCart);

        /* Tecla Escape cierra el carrito */
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeCart();
        });

        /* Todos los botones con [data-cart-open] abren el carrito */
        document.querySelectorAll('[data-cart-open]').forEach(btn => {
            btn.addEventListener('click', openCart);
        });

        /* Botones "Pedir" — vinculan nombre y precio desde data attributes */
        document.querySelectorAll('.btn-pedir').forEach(btn => {
            btn.addEventListener('click', () => {
                addItem(btn.dataset.nombre, btn.dataset.precio);
            });
        });
    }

    /* ── API pública ───────────────────────────────────────── */
    return { init, addItem, removeItem, updateQty, openCart, closeCart };

})();

/* Inicializar el módulo cuando el DOM esté listo */
document.addEventListener('DOMContentLoaded', () => CartModule.init());
