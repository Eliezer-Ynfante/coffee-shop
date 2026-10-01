@extends('admin.layout')

@section('title', 'Control de Galería Fotográfica')

@section('content')

{{-- ENCABEZADO --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-border">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-muted hover:text-cream transition">Dashboard</a>
            <span class="text-xs text-muted">/</span>
            <span class="text-xs text-amber font-medium">Galería</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-cream">Control de Galería Fotográfica</h1>
        <p class="text-xs text-muted mt-1">Sube, ordena o retira fotografías del catálogo visual expuesto en la sección de Galería.</p>
    </div>

    <div class="flex items-center gap-2.5">
        <a href="{{ route('galeria') }}" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg bg-surface border border-border hover:border-amber/40 text-cream transition">
            <span>Ver Galería Pública</span>
            <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-muted"></i>
        </a>

        <button type="button" onclick="document.getElementById('modal-new-photo').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium rounded-lg bg-amber hover:bg-amberLight text-white shadow-sm transition">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Agregar Foto</span>
        </button>
    </div>
</div>

{{-- FILTROS --}}
<div class="flex items-center gap-2 text-xs flex-wrap">
    <a href="{{ route('admin.gallery.index') }}"
       class="px-3 py-1.5 rounded-lg border transition {{ !request('category') ? 'bg-amber/15 text-amber border-amber/30 font-medium' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Todas las Categorías
    </a>
    <a href="{{ route('admin.gallery.index', ['category' => 'cafe']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('category') === 'cafe' ? 'bg-amber/15 text-amber border-amber/30 font-medium' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Café & Barismo
    </a>
    <a href="{{ route('admin.gallery.index', ['category' => 'ambiente']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('category') === 'ambiente' ? 'bg-amber/15 text-amber border-amber/30 font-medium' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Espacios & Local
    </a>
    <a href="{{ route('admin.gallery.index', ['category' => 'postres']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('category') === 'postres' ? 'bg-amber/15 text-amber border-amber/30 font-medium' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Repostería & Postres
    </a>
    <a href="{{ route('admin.gallery.index', ['category' => 'procesos']) }}"
       class="px-3 py-1.5 rounded-lg border transition {{ request('category') === 'procesos' ? 'bg-amber/15 text-amber border-amber/30 font-medium' : 'bg-surface border-border text-muted hover:text-cream' }}">
        Tueste & Origen
    </a>
</div>

{{-- GRID DE FOTOGRAFÍAS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse ($items as $item)
    <div class="bg-surface border border-border hover:border-amber/40 rounded-xl overflow-hidden shadow-lg transition flex flex-col justify-between group">
        {{-- Imagen con Badge y Overlay --}}
        <div class="relative h-44 overflow-hidden bg-dark">
            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            @if ($item->badge)
            <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-black/75 text-amber border border-amber/30 backdrop-blur-sm">
                {{ $item->badge }}
            </span>
            @endif
            <span class="absolute bottom-2.5 left-2.5 px-2 py-0.5 rounded text-[9px] font-mono uppercase bg-black/60 text-cream/90 backdrop-blur-sm">
                {{ $item->category_name ?? $item->category }}
            </span>
        </div>

        {{-- Contenido --}}
        <div class="p-4 flex-1 flex flex-col justify-between space-y-2">
            <div>
                <h3 class="font-semibold text-cream text-xs line-clamp-1">{{ $item->title }}</h3>
                <p class="text-[11px] text-muted line-clamp-2 mt-1">{{ $item->description }}</p>
            </div>

            <div class="pt-3 border-t border-border/60 flex items-center justify-between text-xs">
                <span class="text-[10px] text-muted font-mono">Orden #{{ $item->sort_order }}</span>

                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="editGalleryPhoto({{ json_encode($item) }})"
                            class="p-1 text-muted hover:text-amber rounded transition" title="Editar">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta fotografía de la galería?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 text-muted hover:text-red-400 rounded transition" title="Eliminar">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="sm:col-span-2 lg:col-span-4 bg-surface border border-border rounded-xl p-12 text-center text-muted text-xs">
        No hay fotografías registradas en esta categoría.
    </div>
    @endforelse
</div>

@if ($items->hasPages())
<div class="p-4 border-t border-border">
    {{ $items->links() }}
</div>
@endif

{{-- MODAL AGREGAR FOTO --}}
<div id="modal-new-photo" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-surface border border-border rounded-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-border">
            <h3 class="text-base font-bold text-cream">Agregar Fotografía a Galería</h3>
            <button type="button" onclick="document.getElementById('modal-new-photo').classList.add('hidden')" class="text-muted hover:text-cream">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.gallery.store') }}" method="POST" class="space-y-3.5 text-xs">
            @csrf

            <div>
                <label class="block text-muted mb-1 font-medium">Título de la Fotografía *</label>
                <input type="text" name="title" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="Ej. Ritual de Filtrado Chemex">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-muted mb-1 font-medium">Categoría *</label>
                    <select name="category" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                        <option value="cafe">Café & Barismo</option>
                        <option value="ambiente">Espacios & Local</option>
                        <option value="postres">Repostería & Postres</option>
                        <option value="procesos">Tueste & Origen</option>
                    </select>
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Etiqueta / Badge</label>
                    <input type="text" name="badge" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="Ej. Barismo">
                </div>
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">URL de la Imagen *</label>
                <input type="url" name="image_url" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="https://images.unsplash.com/...">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Descripción</label>
                <textarea name="description" rows="2.5" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber" placeholder="Breve nota explicativa sobre la foto..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border">
                <button type="button" onclick="document.getElementById('modal-new-photo').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border text-muted hover:text-cream">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-amber hover:bg-amberLight text-white font-medium">Guardar en Galería</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR FOTO --}}
<div id="modal-edit-photo" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-surface border border-border rounded-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-border">
            <h3 class="text-base font-bold text-cream">Editar Fotografía</h3>
            <button type="button" onclick="document.getElementById('modal-edit-photo').classList.add('hidden')" class="text-muted hover:text-cream">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-edit-photo" action="" method="POST" class="space-y-3.5 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-muted mb-1 font-medium">Título de la Fotografía *</label>
                <input type="text" id="edit-photo-title" name="title" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-muted mb-1 font-medium">Categoría *</label>
                    <select id="edit-photo-category" name="category" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                        <option value="cafe">Café & Barismo</option>
                        <option value="ambiente">Espacios & Local</option>
                        <option value="postres">Repostería & Postres</option>
                        <option value="procesos">Tueste & Origen</option>
                    </select>
                </div>

                <div>
                    <label class="block text-muted mb-1 font-medium">Etiqueta / Badge</label>
                    <input type="text" id="edit-photo-badge" name="badge" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
                </div>
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">URL de la Imagen *</label>
                <input type="url" id="edit-photo-url" name="image_url" required class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber">
            </div>

            <div>
                <label class="block text-muted mb-1 font-medium">Descripción</label>
                <textarea id="edit-photo-desc" name="description" rows="2.5" class="w-full py-2 px-3 bg-dark border border-border rounded-lg text-cream focus:outline-none focus:border-amber"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border">
                <button type="button" onclick="document.getElementById('modal-edit-photo').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border text-muted hover:text-cream">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-amber hover:bg-amberLight text-white font-medium">Actualizar Foto</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function editGalleryPhoto(photo) {
        document.getElementById('form-edit-photo').action = '/admin/galeria/' + photo.id;
        document.getElementById('edit-photo-title').value = photo.title;
        document.getElementById('edit-photo-category').value = photo.category;
        document.getElementById('edit-photo-badge').value = photo.badge || '';
        document.getElementById('edit-photo-url').value = photo.image_url;
        document.getElementById('edit-photo-desc').value = photo.description || '';

        document.getElementById('modal-edit-photo').classList.remove('hidden');
    }
</script>
@endsection
