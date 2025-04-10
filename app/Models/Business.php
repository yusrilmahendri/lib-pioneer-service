<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Business extends Model
{
    use HasFactory, HasUuid;
    protected $guarded = [''];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
