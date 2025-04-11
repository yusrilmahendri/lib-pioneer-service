<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;
use App\Models\Transaction;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $user = User::skip(1)->first();

        // Ambil business pertama milik user tsb
        $business = Business::where('user_id', $user->id)->first();

        Transaction::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'businesses_id' => $business->id,
            'type_transaction' => 'outcome',
            'amount' => 150000, // misalnya 150 ribu untuk beli bahan pokok
            'descriptions' => "Pembelian bahan pokok: bumbu, sagu dan saos Chickrol",
        ]);
    }
}
