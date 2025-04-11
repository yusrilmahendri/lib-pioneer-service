<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Business;
use App\Models\User;
use App\Models\Category;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::inRandomOrder()->take(2)->get();
        $user = User::inRandomOrder()->first(); // Ambil satu user random

        // Jika tidak ada kategori atau user, hentikan seed
        if ($categories->count() < 2 || !$user) {
            $this->command->warn("Seeder dihentikan: tidak cukup data kategori atau user.");
            return;
        }

        Business::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'category_id' => $categories[0]->id,
            'name' => "Chickenroll",
        ]);

        Business::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'category_id' => $categories[1]->id,
            'name' => "Kopi Nusantara",
        ]);
    }
}
