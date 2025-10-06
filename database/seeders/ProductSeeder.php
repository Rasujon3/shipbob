<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            Product::create([
                'name' => 'Product ' . $i,
                'description' => 'description',
                'price' => '100',
                'file' => 'uploads/products/product_688871a48e4336.00721696.jpg',
                'commission' => '10',
            ]);
        }
    }
}
