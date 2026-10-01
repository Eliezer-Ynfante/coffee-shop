@extends('admin.layout')

@section('title', 'Gestión de Categorías')

@section('content')

{{-- ENCABEZADO --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Categorías</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Familias y Categorías del Menú</h1>
        <p class="text-xs text-muted mt-1">Organiza las secciones principales de la carta: café caliente, bebidas frías, repostería y alimentos.</p>
    </div>

    <div>
        <button type="button" onclick="document.getElementById('modal-new-category').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium rounded-lg bg-amber hover:bg-amberLight text-white shadow-sm transition">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Nueva Categoría</span>
        </button>
    </div>
</div>

{{-- LISTADO DE CATEGORÍAS --}}
<div class="bg-surface border border-border rounded-xl overflow-hidden shadow-lg">
    <table class="w-full text-left text-xs">
        <thead class="bg-dark/80 text-[11px] font-semibold text-muted uppercase tracking-wider border-b border-border">
            <tr>
                <th class="py-3 px-4">Orden</th>
                <th class="py-3 px-4">Categoría</th>
                <th class="py-3 px-4">Slug URL</th>
                <th class="py-3 px-4">Descripción</th>
                <th class="py-3 px-4">Productos</th>
                <th class="py-3 px-4">Estado</th>
                <th class="py-3 px-4 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border/60 text-cream/90 font-sans">
            @forelse ($categories as $cat)
            <tr class="hover:bg-card/40 transition">
                <td class="py-3.5 px-4 font-mono text-muted text-center w-12">
                    #{{ $cat->sort_order }}
                </td>
                <td class="py-3.5 px-4 font-semibold text-cream">
                    {{ $cat->name }}
                </td>
                <td class="py-3.5 px-4 font-mono text-muted text-[11px]">
                    /{{ $cat->slug }}
                </td>
                <td class="py-3.5 px-4 text-muted text-[11px] max-w-xs truncate">
                    {{ $cat->description ?? '—' }}
                </td>
                <td class="py-3.5 px-4">
                    <a href="{{ route('admin.products.index', ['category_id' => $cat->id]) }}"
                       class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-mono bg-card border border-border hover:border-amber/40 text-cream transition">
                        <span>{{ $cat->products_count }}</span>
                        <span class="text-muted text-[10px]">items</span>
                    </a>
                </td>
                <td class="py-3.5 px-4">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $cat->is_active ? 'bg-emerald-500/15 text-emerald-400' : 'bg-zinc-700/30 text-zinc-400' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $cat->is_active ? 'bg-emerald-400' : 'bg-zinc-500' }}"></span>
                        <span>{{ $cat->is_active ? 'Visible' : 'Oculta' }}</span>
                    </span>
                </td>
                <td class="py-3.5 px-4 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <button type="button" onclick="editCategory({{ json_encode($cat) }})"
                                class="p-1.5 text-muted hover:text-amber rounded transition" title="Editar">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?')">
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
                <td colspan="7" class="py-10 text-center text-muted">No hay categorías registradas en el sistema.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODAL CREAR CATEGORÍA --}}
<div id="modal-new-category" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-surface border border-border rounded-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-border">
            <h3 class="text-base font-bold text-cream">Nueva Categoría</h3>
            <button type="button" onclick="document.getElementById('modal-new-category').classList.add('hidden')" class="text-muted hover:text-cream">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-muted mb-1 font-medium">Nombre de la Categoría *</label>
                <input type="text" name="name" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="Ej. Filtrados & Métodos Alternativos">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Orden de Aparición</label>
                <input type="number" name="sort_order" value="1" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Descripción</label>
                <textarea name="description" rows="2.5" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="Breve resumen de esta familia de bebidas o comida..."></textarea>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="is_active" value="1" checked id="new-cat-active" class="rounded border-border text-amber focus:ring-0">
                <label for="new-cat-active" class="text-cream cursor-pointer">Visible en la carta pública</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border">
                <button type="button" onclick="document.getElementById('modal-new-category').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border text-muted hover:text-cream">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-amber hover:bg-amberLight text-white font-medium">Crear Categoría</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR CATEGORÍA --}}
<div id="modal-edit-category" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-surface border border-border rounded-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-border">
            <h3 class="text-base font-bold text-cream">Editar Categoría</h3>
            <button type="button" onclick="document.getElementById('modal-edit-category').classList.add('hidden')" class="text-muted hover:text-cream">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-edit-category" action="" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-muted mb-1 font-medium">Nombre de la Categoría *</label>
                <input type="text" id="edit-cat-name" name="name" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Orden de Aparición</label>
                <input type="number" id="edit-cat-order" name="sort_order" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Descripción</label>
                <textarea id="edit-cat-desc" name="description" rows="2.5" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="edit-cat-active" name="is_active" value="1" class="rounded border-border text-amber focus:ring-0">
                <label for="edit-cat-active" class="text-cream cursor-pointer">Visible en la carta pública</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border">
                <button type="button" onclick="document.getElementById('modal-edit-category').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border text-muted hover:text-cream">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-amber hover:bg-amberLight text-white font-medium">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function editCategory(cat) {
        document.getElementById('form-edit-category').action = '/admin/categorias/' + cat.id;
        document.getElementById('edit-cat-name').value = cat.name;
        document.getElementById('edit-cat-order').value = cat.sort_order;
        document.getElementById('edit-cat-desc').value = cat.description || '';
        document.getElementById('edit-cat-active').checked = !!cat.is_active;

        document.getElementById('modal-edit-category').classList.remove('hidden');
    }
</script>
@endsection
