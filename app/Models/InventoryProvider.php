<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function scopeSearch ($query, $search) {
        if ($search) {
            $query->where('code', 'LIKE', '%' . $search . '%')
                   ->orWhere('name', 'LIKE', '%' . $search . '%');
        }
    }
}
