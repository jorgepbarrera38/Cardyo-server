<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turn extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'time_start',
        'time_finish',
    ];

    public function scopeSearch ($query, $search) {
        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }
    }
}
