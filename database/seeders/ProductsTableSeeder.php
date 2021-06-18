<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'Combo de perros',
                'description' => 'Combo de 2 perro sencillos',
                'price' => 1.00,
                'qty_available' => 50,
                'qty_active' => 25
            ],
            [
                'name' => 'Combo de hamburguesas',
                'description' => 'Combo de 2 hamburguesas sencillas',
                'price' => 1.00,
                'qty_available' => 50,
                'qty_active' => 25
            ],
            [
                'name' => 'Pepito mixto',
                'description' => 'Pepito de pan canilla con carne, pollo y chuleta',
                'price' => 5.00,
                'qty_available' => 50,
                'qty_active' => 25
            ],
        ];

        Product::insert($products);
    }
}
