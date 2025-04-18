<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user = User::create([
            'id' => Str::uuid(),
            'name' => 'admin-chickroll',
            'email' => 'admin-chickroll@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Bismillah@1'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('user');
        return $user;
    }
}
