@extends('admin.layout')

@section('title', 'Gestión de Productos')

@section('content')

{{-- ENCABEZADO --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Catálogo</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Control de Productos y Menú</h1>
        <p class="text-xs text-muted mt-1">Administra los precios, ingredientes, stock y visibilidad en la carta de la cafetería.</p>
    </div>

    <div>
        <button type="button" onclick="document.getElementById('modal-new-product').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium rounded-lg bg-amber hover:bg-amberLight text-white shadow-sm transition">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Nuevo Producto</span>
        </button>
    </div>
</div>

{{-- FILTROS Y BÚSQUEDA --}}
<form method="GET" action="{{ route('admin.products.index') }}" class="bg-surface border border-border rounded-xl p-4 flex flex-col md:flex-row items-center gap-3">
    <div class="relative flex-1 w-full">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-muted"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o código SKU..."
               class="w-full pl-9 pr-3 py-2 bg-dark/60 border border-border rounded-lg text-xs text-cream placeholder-muted focus:outline-none focus:border-amber transition">
    </div>

    <div class="w-full md:w-56">
        <select name="category_id" onchange="this.form.submit()" class="w-full py-2 px-3 bg-dark/60 border border-border rounded-lg text-xs text-cream focus:outline-none focus:border-amber transition">
            <option value="">Todas las Categorías</option>
            @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="w-full md:w-44">
        <select name="status" onchange="this.form.submit()" class="w-full py-2 px-3 bg-dark/60 border border-border rounded-lg text-xs text-cream focus:outline-none focus:border-amber transition">
            <option value="">Todos los Estados</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
        </select>
    </div>

    @if (request()->hasAny(['search', 'category_id', 'status']))
    <a href="{{ route('admin.products.index') }}" class="px-3 py-2 text-xs text-muted hover:text-cream border border-border rounded-lg transition whitespace-nowrap">
        Limpiar
    </a>
    @endif
</form>

{{-- TABLA DE PRODUCTOS --}}
<div class="bg-surface border border-border rounded-xl overflow-hidden shadow-lg">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-dark/80 text-[11px] font-semibold text-muted uppercase tracking-wider border-b border-border">
                <tr>
                    <th class="py-3 px-4">Producto</th>
                    <th class="py-3 px-4">Categoría</th>
                    <th class="py-3 px-4">Precio Venta</th>
                    <th class="py-3 px-4">Costo / Margen</th>
                    <th class="py-3 px-4">Stock</th>
                    <th class="py-3 px-4">Estado</th>
                    <th class="py-3 px-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border/60 text-cream/90 font-sans">
                @forelse ($products as $prod)
                <tr class="hover:bg-card/40 transition">
                    {{-- Producto --}}
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-card border border-border overflow-hidden shrink-0 flex items-center justify-center text-amber">
                                @if ($prod->image_path)
                                <img src="{{ $prod->image_path }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                @else
                                <i class="fa-solid fa-mug-hot text-sm"></i>
                                @endif
                            </div>
                            <div>
                                <div class="font-semibold text-cream flex items-center gap-2">
                                    <span>{{ $prod->name }}</span>
                                    @if ($prod->is_featured)
                                    <span class="text-[9px] bg-amber/20 text-amber px-1.5 py-0.5 rounded font-mono uppercase">Destacado</span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-muted font-mono">{{ $prod->sku ?? 'S/N' }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- Categoría --}}
                    <td class="py-3.5 px-4">
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-card border border-border text-cream/80">
                            {{ $prod->category->name ?? 'Sin categoría' }}
                        </span>
                    </td>

                    {{-- Precio --}}
                    <td class="py-3.5 px-4 font-mono font-medium text-cream">
                        S/ {{ number_format($prod->price, 2) }}
                    </td>

                    {{-- Costo --}}
                    <td class="py-3.5 px-4 font-mono text-muted">
                        @if ($prod->cost_price)
                        <span>S/ {{ number_format($prod->cost_price, 2) }}</span>
                        <span class="text-[10px] text-emerald-400 block font-sans">
                            +{{ number_format((($prod->price - $prod->cost_price) / $prod->price) * 100, 0) }}% margen
                        </span>
                        @else
                        <span>—</span>
                        @endif
                    </td>

                    {{-- Stock --}}
                    <td class="py-3.5 px-4">
                        <span class="font-mono text-xs px-2 py-0.5 rounded {{ $prod->stock <= $prod->min_stock_alert ? 'bg-red-500/15 text-red-400 font-bold' : 'bg-emerald-500/10 text-emerald-300' }}">
                            {{ $prod->stock }} un.
                        </span>
                    </td>

                    {{-- Estado --}}
                    <td class="py-3.5 px-4">
                        <form action="{{ route('admin.products.toggle', $prod->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium transition {{ $prod->is_active ? 'bg-emerald-500/15 text-emerald-400 hover:bg-emerald-500/25' : 'bg-zinc-700/30 text-zinc-400 hover:bg-zinc-700/50' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $prod->is_active ? 'bg-emerald-400' : 'bg-zinc-500' }}"></span>
                                <span>{{ $prod->is_active ? 'Disponible' : 'Agotado / Oculto' }}</span>
                            </button>
                        </form>
                    </td>

                    {{-- Acciones --}}
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            {{-- Botón Editar --}}
                            <button type="button" onclick="editProduct({{ json_encode($prod) }})"
                                    class="p-1.5 text-muted hover:text-amber rounded transition" title="Editar Producto">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            {{-- Botón Eliminar --}}
                            <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-muted hover:text-red-400 rounded transition" title="Eliminar">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-muted">
                        No se encontraron productos registrados con los criterios seleccionados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->hasPages())
    <div class="p-4 border-t border-border">
        {{ $products->links() }}
    </div>
    @endif
</div>

{{-- MODAL CREAR PRODUCTO --}}
<div id="modal-new-product" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-surface border border-border rounded-xl max-w-lg w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-border">
            <h3 class="text-base font-bold text-cream">Registrar Nuevo Producto</h3>
            <button type="button" onclick="document.getElementById('modal-new-product').classList.add('hidden')" class="text-muted hover:text-cream">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="sm:col-span-2">
                    <label class="block text-muted mb-1 font-medium">Nombre del Producto *</label>
                    <input type="text" name="name" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="Ej. Espresso Doble Origen">
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Categoría *</label>
                    <select name="category_id" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Código SKU</label>
                    <input type="text" name="sku" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="CAF-ESP-01">
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Precio Venta (S/) *</label>
                    <input type="number" step="0.10" name="price" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="12.00">
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Costo Estimado (S/)</label>
                    <input type="number" step="0.10" name="cost_price" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="4.50">
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Stock Inicial *</label>
                    <input type="number" name="stock" value="20" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">URL de Imagen</label>
                    <input type="text" name="image_path" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="https://...">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-muted mb-1 font-medium">Descripción / Notas de Cata</label>
                    <textarea name="description" rows="3" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="Notas florales, acidez cítrica y cuerpo cremoso..."></textarea>
                </div>

                <div class="sm:col-span-2 flex items-center gap-6 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-border text-amber focus:ring-0">
                        <span class="text-cream">Activo en carta</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-border text-amber focus:ring-0">
                        <span class="text-cream">Producto Destacado</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border">
                <button type="button" onclick="document.getElementById('modal-new-product').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border text-muted hover:text-cream">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-amber hover:bg-amberLight text-white font-medium">Guardar Producto</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR PRODUCTO --}}
<div id="modal-edit-product" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-surface border border-border rounded-xl max-w-lg w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-border">
            <h3 class="text-base font-bold text-cream">Editar Producto</h3>
            <button type="button" onclick="document.getElementById('modal-edit-product').classList.add('hidden')" class="text-muted hover:text-cream">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-edit-product" action="" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="sm:col-span-2">
                    <label class="block text-muted mb-1 font-medium">Nombre del Producto *</label>
                    <input type="text" id="edit-name" name="name" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Categoría *</label>
                    <select id="edit-category_id" name="category_id" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Código SKU</label>
                    <input type="text" id="edit-sku" name="sku" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Precio Venta (S/) *</label>
                    <input type="number" step="0.10" id="edit-price" name="price" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Costo Estimado (S/)</label>
                    <input type="number" step="0.10" id="edit-cost_price" name="cost_price" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Stock *</label>
                    <input type="number" id="edit-stock" name="stock" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">URL de Imagen</label>
                    <input type="text" id="edit-image_path" name="image_path" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-muted mb-1 font-medium">Descripción / Notas de Cata</label>
                    <textarea id="edit-description" name="description" rows="3" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber"></textarea>
                </div>

                <div class="sm:col-span-2 flex items-center gap-6 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="edit-is_active" name="is_active" value="1" class="rounded border-border text-amber focus:ring-0">
                        <span class="text-cream">Activo en carta</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="edit-is_featured" name="is_featured" value="1" class="rounded border-border text-amber focus:ring-0">
                        <span class="text-cream">Producto Destacado</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border">
                <button type="button" onclick="document.getElementById('modal-edit-product').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border text-muted hover:text-cream">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-amber hover:bg-amberLight text-white font-medium">Actualizar Cambios</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function editProduct(product) {
        document.getElementById('form-edit-product').action = '/admin/productos/' + product.id;
        document.getElementById('edit-name').value = product.name;
        document.getElementById('edit-category_id').value = product.category_id;
        document.getElementById('edit-sku').value = product.sku || '';
        document.getElementById('edit-price').value = product.price;
        document.getElementById('edit-cost_price').value = product.cost_price || '';
        document.getElementById('edit-stock').value = product.stock;
        document.getElementById('edit-image_path').value = product.image_path || '';
        document.getElementById('edit-description').value = product.description || '';
        document.getElementById('edit-is_active').checked = !!product.is_active;
        document.getElementById('edit-is_featured').checked = !!product.is_featured;

        document.getElementById('modal-edit-product').classList.remove('hidden');
    }
</script>
@endsection
