# Informe de revisión — coffee-shop

Alcance: `app`, `config`, `routes`, `resources`. Comprobado con `php artisan about`, `route:list`, `php artisan test` y Pint.

**Conclusión:** no hay bypass de admin ni SQL crudo. CSRF y roles están bien. El riesgo real está en los formularios públicos, URLs libres y datos duplicados (`config` vs `setting()`).

## Estado

| Comando | Resultado |
|---|---|
| `php artisan about` | Laravel 13.4 · PHP 8.4 · **debug ENABLED** · `public/storage` **no enlazado** |
| `php artisan route:list` | 44 rutas |
| `php artisan test` | 1 falló, 1 pasó: `/` espera 200 y recibe 500 porque PHPUnit usa SQLite `:memory:` y falta `pdo_sqlite` |
| Pint | Falla en `app`, `routes` y `config` |

Admin queda detrás de `auth` + `role:admin`. CSRF en formularios. Contraseñas con hash.

---

## Vulnerabilidades

| Severidad | Ubicación | Hallazgo |
|---|---|---|
| **Alta** | `public/js/scripts.js` ~204 y ~394 | Contacto y reserva hacen `fetch` y muestran éxito aunque el POST falle (`catch` vacío). El voucher de reserva inventa un código distinto al de BD. |
| **Alta** | `app/Http/Controllers/ReservaController.php:60` | Toda reserva se guarda como `confirmed` sin validar mesa, solape ni fecha pasada. |
| **Alta** | `routes/web.php` login / reserva / contacto | Sin `throttle`: fuerza bruta en login y spam de PII. |
| **Media** | `app/Http/Controllers/AdminController.php:522`, productos/galería | Ajustes sin validar email/URL. `image_path` / `image_url` / redes aceptan `javascript:` u orígenes arbitrarios (XSS / open redirect). |
| **Media** | Vistas públicas vs `setting()` | Guardar ajustes **no cambia** layout, contacto, reserva, nosotros ni carta: siguen `config('cafe.*')`. |
| **Media** | `config/filesystems.php:36` y `.env` | Disco local con `serve=true` registra GET/PUT `storage/{path}`. En producción, `APP_DEBUG=true` filtra stack traces. |
| **Media** | `public/js/scripts.js:311` | `innerHTML` con nombre de mesa/zona: HTML malicioso en BD se ejecuta en `/reserva`. |
| **Baja** | `resources/views/layout/layout.blade.php:19` | Si no hay build Vite, se carga Tailwind por CDN (cadena de suministro). |
| **Baja** | `database/seeders/DatabaseSeeder.php:22` | Usuarios seed con `password`. Peligroso si se siembra fuera de local. |

---

## Sprint de mejoras

### Sprint 1 — seguridad (P0)

1. `throttle:5,1` en login; `throttle:10,1` en reserva y contacto.
2. Validar mesa existente, `fecha >= hoy`, sin solape; guardar como `pending`.
3. Mostrar el error real del servidor; usar el `id` de BD en el voucher.
4. URLs solo `https` (allowlist) en imágenes y redes.
5. Producción: `APP_DEBUG=false`, cookie de sesión `secure`, no seedear claves débiles.

### Sprint 2 — código que no funciona (P1)

1. Unificar `setting()` en todas las vistas públicas (hoy solo footer/navbar/hero parcial).
2. `carta.blade.php` itera `config('cafe.productos')` e ignora productos de BD.
3. Relación `User → Customer → Order`: un cliente sin fila en `customers` ve el portal vacío.
4. Hacer que PHPUnit use MySQL o habilitar `pdo_sqlite` (el test de `/` da 500).

### Sprint 3 — espagueti (P2)

1. Partir `AdminController` (~543 líneas) en controladores por dominio.
2. Form Requests; slugs únicos en categorías.
3. Un solo pipeline Vite; quitar CDN de Tailwind y el CSS duplicado en `public/css`.
4. Quitar `AuthController::dashboard()`, imports `DB` no usados y `scratch/check_db.php`.
5. Correr Pint en `app`, `routes` y `config`.

---

## Qué está bien

- CSRF en formularios y meta token
- Admin con `auth` + `role`
- Password hashed en `User`
- Eloquent / query builder (sin SQL crudo)
- Logout regenera sesión
