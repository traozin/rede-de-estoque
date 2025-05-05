<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder {
    public function run(): void {
        $products = [];
        for ($i = 1; $i <= 150; $i++) {
            $products[] = [
                'name' => 'Product ' . $i,
                'description' => 'Description for Product ' . $i,
                'quantity' => rand(1, 100),
                'price' => rand(100, 10000) / 100,
                'category' => 'Category ' . rand(1, 10),
                'sku' => 'SKU-' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('products')->insert($products);
    }
}