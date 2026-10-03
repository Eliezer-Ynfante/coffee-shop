<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Product;

class PublicPageController extends Controller
{
    /**
     * Portada Principal (Welcome / Home)
     */
    public function home()
    {
        $menuCategorias = $this->getMenuCategorias();
        $productosDestacados = $this->getProductosDestacados();

        return view('welcome', compact('menuCategorias', 'productosDestacados'));
    }

    /**
     * Carta y Menú Completo
     */
    public function carta()
    {
        $menuCategorias = $this->getMenuCategorias();
        $productosCarta = Product::where('is_active', true)
            ->where('available_in_store', true)
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'nombre' => $product->name,
                    'descripcion' => $product->description,
                    'precio' => 'S/ '.number_format($product->price, 0),
                    'imagen' => $product->image_path ?: 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=600&q=80&fm=webp',
                    'badge' => $product->is_featured ? 'Destacado' : null,
                ];
            })
            ->all();

        if ($productosCarta === []) {
            $productosCarta = config('cafe.productos', []);
        }

        return view('carta', compact('menuCategorias', 'productosCarta'));
    }

    /**
     * Galería Fotográfica
     */
    public function galeria()
    {
        $dbItems = GalleryItem::where('is_active', true)->orderBy('sort_order')->get();

        if ($dbItems->isNotEmpty()) {
            $galeria = $dbItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'titulo' => $item->title,
                    'categoria' => $item->category,
                    'categoria_nombre' => $item->category_name ?? ucfirst($item->category),
                    'descripcion' => $item->description,
                    'imagen' => $item->image_url,
                    'badge' => $item->badge,
                ];
            })->toArray();
        } else {
            $galeria = config('cafe.galeria', []);
        }

        $galeriaCategorias = config('cafe.galeria_categorias', []);

        return view('galeria', compact('galeria', 'galeriaCategorias'));
    }

    /**
     * Obtiene las categorías de la BD con fallback a config/cafe.php
     */
    protected function getMenuCategorias(): array
    {
        $dbCategories = Category::with(['products' => function ($q) {
            $q->where('is_active', true)->where('available_in_store', true)->orderBy('id');
        }])->where('is_active', true)->orderBy('sort_order')->get();

        if ($dbCategories->isNotEmpty()) {
            return $dbCategories->map(function ($cat) {
                return [
                    'nombre' => $cat->name,
                    'imagen' => $cat->image_path ?: 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=420&q=80&fm=webp',
                    'items' => $cat->products->map(function ($prod) {
                        return [
                            'nombre' => $prod->name,
                            'descripcion' => $prod->description,
                            'precio' => 'S/ '.number_format($prod->price, 0),
                        ];
                    })->toArray(),
                ];
            })->toArray();
        }

        return config('cafe.menu_categorias', []);
    }

    /**
     * Obtiene productos destacados de la BD con fallback a config/cafe.php
     */
    protected function getProductosDestacados(): array
    {
        $dbFeatured = Product::where('is_featured', true)
            ->where('is_active', true)
            ->take(6)
            ->get();

        if ($dbFeatured->isNotEmpty()) {
            return $dbFeatured->map(function ($prod) {
                return [
                    'nombre' => $prod->name,
                    'descripcion' => $prod->description,
                    'precio' => 'S/ '.number_format($prod->price, 0),
                    'imagen' => $prod->image_path ?: 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=600&q=80&fm=webp',
                    'badge' => 'Destacado',
                ];
            })->toArray();
        }

        return config('cafe.productos', []);
    }
}
