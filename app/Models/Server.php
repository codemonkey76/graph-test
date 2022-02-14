<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = ['api_password'];

    protected $casts = ['active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('Active', true);
    }
}
