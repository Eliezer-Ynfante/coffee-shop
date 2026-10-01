<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Categorías: " . \App\Models\Category::count() . "\n";
echo "Productos: " . \App\Models\Product::count() . "\n";
echo "Ajustes (Settings): " . \App\Models\Setting::count() . "\n";
echo "Galería (Items): " . \App\Models\GalleryItem::count() . "\n";
echo "Mesas 3D: " . \App\Models\CafeTable::count() . "\n";
echo "Prueba setting('nombre'): " . setting('nombre') . "\n";
echo "Prueba setting('telefono'): " . setting('telefono') . "\n";
