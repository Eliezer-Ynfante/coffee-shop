<?php

namespace App\Http\Controllers;

use App\Models\CafeTable;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\GalleryItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private const IMAGE_HOSTS = ['images.unsplash.com', 'raizygrano.pe', 'www.raizygrano.pe'];

    private const SOCIAL_HOSTS = [
        'whatsapp' => ['wa.me', 'api.whatsapp.com', 'whatsapp.com', 'www.whatsapp.com'],
        'instagram' => ['instagram.com', 'www.instagram.com'],
        'facebook' => ['facebook.com', 'www.facebook.com', 'm.facebook.com', 'fb.com', 'www.fb.com'],
        'tiktok' => ['tiktok.com', 'www.tiktok.com'],
    ];

    /**
     * Dashboard general del panel de administración.
     */
    public function dashboard()
    {
        $stats = [
            'total_users'        => User::count(),
            'total_products'     => Product::count(),
            'total_categories'   => Category::count(),
            'total_orders'       => Order::count(),
            'total_reservations' => Reservation::count(),
            'unread_messages'    => ContactMessage::where('status', 'unread')->count(),
            'active_orders'      => Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])->count(),
            'pending_reservas'   => Reservation::where('status', 'pending')->count(),
        ];

        $recentOrders = Order::with('items.product')
            ->latest()
            ->take(5)
            ->get();

        $upcomingReservations = Reservation::whereDate('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->orderBy('hora')
            ->take(5)
            ->get();

        $recentMessages = ContactMessage::latest()
            ->take(4)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'upcomingReservations', 'recentMessages'));
    }

    /**
     * ── PRODUCTOS / MENÚ ───────────────────────────────────────
     */
    public function products(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:150',
            'sku'         => 'nullable|string|max:50|unique:products,sku',
            'price'       => 'required|numeric|min:0',
            'cost_price'  => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image_path'  => ['nullable', 'string', 'max:255', $this->imagePathRule()],
            'is_active'   => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('status', 'Producto creado exitosamente.');
    }

    public function updateProduct(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:150',
            'sku'         => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'price'       => 'required|numeric|min:0',
            'cost_price'  => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image_path'  => ['nullable', 'string', 'max:255', $this->imagePathRule()],
            'is_active'   => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('status', "Producto '{$product->name}' actualizado correctamente.");
    }

    public function toggleProductStatus(int $id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        return back()->with('status', "Estado del producto '{$product->name}' actualizado.");
    }

    public function destroyProduct(int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', "Producto eliminado correctamente.");
    }

    /**
     * ── CATEGORÍAS ─────────────────────────────────────────────
     */
    public function categories()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('status', 'Categoría creada con éxito.');
    }

    public function updateCategory(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('status', "Categoría '{$category->name}' actualizada.");
    }

    public function destroyCategory(int $id)
    {
        $category = Category::withCount('products')->findOrFail($id);

        if ($category->products_count > 0) {
            return back()->withErrors(['error' => "No se puede eliminar la categoría porque contiene {$category->products_count} productos asociados."]);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Categoría eliminada con éxito.');
    }

    /**
     * ── RESERVAS ───────────────────────────────────────────────
     */
    public function reservations(Request $request)
    {
        $query = Reservation::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        $reservations = $query->paginate(15)->withQueryString();

        $statusCounts = [
            'total'     => Reservation::count(),
            'pending'   => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'statusCounts'));
    }

    public function updateReservationStatus(Request $request, int $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $reservation->update(['status' => $validated['status']]);

        return back()->with('status', "Reserva #{$reservation->id} de {$reservation->nombre} actualizada a estado: " . ucfirst($validated['status']) . ".");
    }

    public function destroyReservation(int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return back()->with('status', 'Reserva eliminada.');
    }

    /**
     * ── ÓRDENES / VENTAS ───────────────────────────────────────
     */
    public function orders(Request $request)
    {
        $query = Order::with('items.product')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        $orders = $query->paginate(15)->withQueryString();

        $orderStats = [
            'total'     => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready'     => Order::where('status', 'ready')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'sales'     => Order::where('status', 'completed')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'orderStats'));
    }

    public function updateOrderStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
        ]);

        $updates = ['status' => $validated['status']];

        if ($validated['status'] === 'preparing' && !$order->prepared_at) {
            $updates['prepared_at'] = now();
        }
        if ($validated['status'] === 'completed' && !$order->completed_at) {
            $updates['completed_at'] = now();
        }
        if ($validated['status'] === 'cancelled' && !$order->cancelled_at) {
            $updates['cancelled_at'] = now();
        }

        $order->update($updates);

        return back()->with('status', "Orden {$order->order_number} actualizada a: " . ucfirst($validated['status']) . ".");
    }

    /**
     * ── MENSAJES DE CONTACTO ───────────────────────────────────
     */
    public function messages(Request $request)
    {
        $query = ContactMessage::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(15)->withQueryString();

        $unreadCount = ContactMessage::where('status', 'unread')->count();

        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    public function updateMessageStatus(Request $request, int $id)
    {
        $message = ContactMessage::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:unread,read,attended',
        ]);

        $message->update(['status' => $validated['status']]);

        return back()->with('status', 'Estado del mensaje actualizado.');
    }

    public function destroyMessage(int $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return back()->with('status', 'Mensaje eliminado.');
    }

    /**
     * ── USUARIOS ───────────────────────────────────────────────
     */
    public function users(Request $request)
    {
        $query = User::latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role' => 'required|in:admin,customer',
        ]);

        // Evitar que el admin se quite su propio rol
        if ($user->getAuthIdentifier() === $request->user()?->getAuthIdentifier() && $validated['role'] !== 'admin') {
            return back()->withErrors(['error' => 'No puedes remover tu propio rol de administrador.']);
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('status', "Rol de usuario '{$user->name}' actualizado a: " . ucfirst($validated['role']) . ".");
    }

    /**
     * ── GALERÍA FOTOGRÁFICA ────────────────────────────────────
     */
    public function gallery(Request $request)
    {
        $query = GalleryItem::orderBy('sort_order');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $items = $query->paginate(12)->withQueryString();
        $categories = config('cafe.galeria_categorias', []);

        return view('admin.gallery.index', compact('items', 'categories'));
    }

    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:150',
            'category'    => 'required|string|max:50',
            'image_url'   => ['required', 'url:https', 'max:500', $this->httpsUrlRule(self::IMAGE_HOSTS)],
            'description' => 'nullable|string',
            'badge'       => 'nullable|string|max:50',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $catNames = [
            'cafe'     => 'Café & Barismo',
            'ambiente' => 'Espacios & Local',
            'postres'  => 'Repostería & Postres',
            'procesos' => 'Tueste & Origen',
        ];

        $validated['category_name'] = $catNames[$validated['category']] ?? ucfirst($validated['category']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? (GalleryItem::max('sort_order') + 1);

        GalleryItem::create($validated);

        return redirect()->route('admin.gallery.index')->with('status', 'Foto agregada a la galería con éxito.');
    }

    public function updateGallery(Request $request, int $id)
    {
        $item = GalleryItem::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:150',
            'category'    => 'required|string|max:50',
            'image_url'   => ['required', 'url:https', 'max:500', $this->httpsUrlRule(self::IMAGE_HOSTS)],
            'description' => 'nullable|string',
            'badge'       => 'nullable|string|max:50',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $catNames = [
            'cafe'     => 'Café & Barismo',
            'ambiente' => 'Espacios & Local',
            'postres'  => 'Repostería & Postres',
            'procesos' => 'Tueste & Origen',
        ];

        $validated['category_name'] = $catNames[$validated['category']] ?? ucfirst($validated['category']);
        $validated['is_active'] = $request->boolean('is_active');

        $item->update($validated);

        return redirect()->route('admin.gallery.index')->with('status', 'Foto actualizada correctamente.');
    }

    public function destroyGallery(int $id)
    {
        $item = GalleryItem::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.gallery.index')->with('status', 'Foto eliminada de la galería.');
    }

    /**
     * ── MESAS 3D Y ZONAS ───────────────────────────────────────
     */
    public function tables(Request $request)
    {
        $query = CafeTable::orderBy('zone')->orderBy('code');

        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tables = $query->get();

        $stats = [
            'total'       => CafeTable::count(),
            'disponible'  => CafeTable::where('status', 'disponible')->count(),
            'ocupada'     => CafeTable::where('status', 'ocupada')->count(),
            'reservada'   => CafeTable::where('status', 'reservada')->count(),
        ];

        return view('admin.tables.index', compact('tables', 'stats'));
    }

    public function updateTableStatus(Request $request, int $id)
    {
        $table = CafeTable::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:disponible,ocupada,reservada,mantenimiento',
        ]);

        $table->update(['status' => $validated['status']]);

        return back()->with('status', "Mesa {$table->code} actualizada a: " . ucfirst($validated['status']) . ".");
    }

    /**
     * ── AJUSTES GENERALES DEL SITIO ────────────────────────────
     */
    public function settings()
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'nombre'           => 'sometimes|required|string|max:150',
            'slogan'           => 'sometimes|nullable|string|max:255',
            'titulo'           => 'sometimes|nullable|string|max:255',
            'subtitulo'        => 'sometimes|nullable|string|max:2000',
            'descripcion'      => 'sometimes|nullable|string|max:2000',
            'subtag'           => 'sometimes|nullable|string|max:255',
            'horario'          => 'sometimes|nullable|string|max:255',
            'direccion'        => 'sometimes|nullable|string|max:255',
            'email'            => 'sometimes|nullable|email|max:150',
            'telefono'         => 'sometimes|nullable|string|max:30',
            'hero_img'         => ['sometimes', 'nullable', 'string', 'max:500', $this->imagePathRule()],
            'about_img'        => ['sometimes', 'nullable', 'string', 'max:500', $this->imagePathRule()],
            'redes'            => 'sometimes|array:whatsapp,instagram,facebook,tiktok',
            'redes.whatsapp'   => ['nullable', 'string', 'max:255', $this->httpsUrlRule(self::SOCIAL_HOSTS['whatsapp'])],
            'redes.instagram'  => ['nullable', 'string', 'max:255', $this->httpsUrlRule(self::SOCIAL_HOSTS['instagram'])],
            'redes.facebook'   => ['nullable', 'string', 'max:255', $this->httpsUrlRule(self::SOCIAL_HOSTS['facebook'])],
            'redes.tiktok'     => ['nullable', 'string', 'max:255', $this->httpsUrlRule(self::SOCIAL_HOSTS['tiktok'])],
        ]);

        $keys = [
            'nombre', 'slogan', 'titulo', 'subtitulo', 'descripcion',
            'subtag', 'horario', 'direccion', 'email', 'telefono',
            'hero_img', 'about_img'
        ];

        foreach ($keys as $key) {
            if (array_key_exists($key, $validated)) {
                Setting::set($key, $validated[$key], 'general');
            }
        }

        // Redes sociales (JSON)
        if (array_key_exists('redes', $validated)) {
            Setting::set('redes', $validated['redes'], 'contacto', 'json');
        }

        return back()->with('status', 'Ajustes de la cafetería actualizados correctamente.');
    }

    private function imagePathRule(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            $path = is_string($value) ? parse_url($value, PHP_URL_PATH) : null;
            $segments = is_string($path) ? explode('/', trim($path, '/')) : [];
            $isLocalImage = is_string($value)
                && (str_starts_with($value, '/storage/') || str_starts_with($value, '/images/'))
                && ! in_array('..', $segments, true)
                && ! str_contains($value, '\\');

            if ($isLocalImage) {
                return;
            }

            $this->validateHttpsUrl($attribute, $value, self::IMAGE_HOSTS, $fail);
        };
    }

    private function httpsUrlRule(array $allowedHosts): \Closure
    {
        return fn (string $attribute, mixed $value, \Closure $fail) => $this->validateHttpsUrl($attribute, $value, $allowedHosts, $fail);
    }

    private function validateHttpsUrl(string $attribute, mixed $value, array $allowedHosts, \Closure $fail): void
    {
        $parts = is_string($value) ? parse_url($value) : false;
        $host = is_array($parts) ? strtolower($parts['host'] ?? '') : '';
        $isAllowed = is_array($parts)
            && strtolower($parts['scheme'] ?? '') === 'https'
            && filter_var($value, FILTER_VALIDATE_URL)
            && in_array($host, $allowedHosts, true);

        if (! $isAllowed) {
            $fail('El campo :attribute debe usar HTTPS y un dominio permitido.');
        }
    }
}
