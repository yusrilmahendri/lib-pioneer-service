<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Business;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function business() {
        return $this->belongsTo(Business::class, 'businesses_id');
    }

     public static function totalPerHari($userId = null)
    {
        return static::when($userId, fn ($query) => $query->where('user_id', $userId))
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');
    }

    public static function totalPerMinggu($userId = null)
    {
        return static::when($userId, fn ($query) => $query->where('user_id', $userId))
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('amount');
    }

    public static function totalPerBulan($userId = null)
    {
        return static::when($userId, fn ($query) => $query->where('user_id', $userId))
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
    }

}
