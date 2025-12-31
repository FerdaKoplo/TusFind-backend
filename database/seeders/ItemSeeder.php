<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = Category::where('name', 'Electronics')->first();
        $documents = Category::where('name', 'Documents')->first();

        $items = [
            [
                'name' => 'iPhone 13',
                'brand' => 'Apple',
                'color' => 'Black',
                'category_id' => $electronics?->id,
            ],
            [
                'name' => 'Samsung Galaxy Buds',
                'brand' => 'Samsung',
                'color' => 'White',
                'category_id' => $electronics?->id,
            ],
            [
                'name' => 'Student ID Card',
                'brand' => null,
                'color' => null,
                'category_id' => $documents?->id,
            ],
            [
                'name' => 'Wallet',
                'brand' => null,
                'color' => 'Brown',
                'category_id' => null, 
            ],
        ];

        foreach ($items as $item) {
            Item::firstOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
