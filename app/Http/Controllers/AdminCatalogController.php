<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryFormRequest;
use App\Http\Requests\GalleryItemFormRequest;
use App\Http\Requests\ProductFormRequest;
use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCatalogController extends Controller
{
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

    public function storeProduct(ProductFormRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(5);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('status', 'Producto creado exitosamente.');
    }

    public function updateProduct(ProductFormRequest $request, int $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $product->update($validated);

        return redirect()->route('admin.products.index')->with('status', "Producto '{$product->name}' actualizado correctamente.");
    }

    public function toggleProductStatus(int $id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = ! $product->is_active;
        $product->save();

        return back()->with('status', "Estado del producto '{$product->name}' actualizado.");
    }

    public function destroyProduct(int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Producto eliminado correctamente.');
    }

    public function categories()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(CategoryFormRequest $request)
    {
        $validated = $request->validated();
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);
        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('status', 'Categoría creada con éxito.');
    }

    public function updateCategory(CategoryFormRequest $request, int $id)
    {
        $category = Category::findOrFail($id);
        $validated = $request->validated();
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

    public function storeGallery(GalleryItemFormRequest $request)
    {
        $validated = $request->validated();
        $validated['category_name'] = $this->categoryName($validated['category']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? (GalleryItem::max('sort_order') + 1);
        GalleryItem::create($validated);

        return redirect()->route('admin.gallery.index')->with('status', 'Foto agregada a la galería con éxito.');
    }

    public function updateGallery(GalleryItemFormRequest $request, int $id)
    {
        $item = GalleryItem::findOrFail($id);
        $validated = $request->validated();
        $validated['category_name'] = $this->categoryName($validated['category']);
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

    private function categoryName(string $category): string
    {
        return [
            'cafe' => 'Café & Barismo',
            'ambiente' => 'Espacios & Local',
            'postres' => 'Repostería & Postres',
            'procesos' => 'Tueste & Origen',
        ][$category] ?? ucfirst($category);
    }
}
