<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Business;
use App\Models\Category;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $categories = Category::all();

        foreach (range(1, 2) as $i) {
            $category = $categories->random(); // ambil acak dari category yang sudah ada

            Business::create([
                'id' => Str::uuid(),
                'category_id' => $category->id,
                'name' => "Business $i",
            ]);
        }
    }
}
