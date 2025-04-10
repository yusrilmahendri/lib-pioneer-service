<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
          $user = User::create([
            'name' => 'admin-pioneer',
            'email' => 'pioneerSolve@gmail.com', 
            'password' => bcrypt('Bismillah@1'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('admin');
        $user->createToken('auth_token')->plainTextToken;
        return $user;
    }
}
