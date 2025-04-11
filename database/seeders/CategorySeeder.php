<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            "UMKM",
            "Perkebunan",
            "Coffe Shop",
            "Toko",
            "Perikanan",
            "Pengepul",
            "Sorum",
            "Konter Elektronik (HP, dll)",
        ];

        foreach ($categories as $name) {
            Category::create([
                'id' => Str::uuid(),
                'name' => $name,
            ]);
        }
    }
}
