<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'product_name' => 'Coca Cola 500ml',
            'product_description' => 'Bebida gaseosa refrescante',
            'product_type' => 'botella',
            'unit_of_measurement' => 'mililitros',
            'net_content' => 500
        ]);

        Product::create([
            'product_name' => 'Arroz Premium 1Kg',
            'product_description' => 'Arroz blanco de grano largo',
            'product_type' => 'bolsa',
            'unit_of_measurement' => 'kilogramos',
            'net_content' => 1
        ]);
    }
}
