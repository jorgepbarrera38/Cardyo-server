<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTweak extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'observations',
        'user_create_id'
    ];

    public function tweakDetails () {
        return $this->hasMany('App\Models\InventoryTweakDetail');
    }

    public function scopeSearch ($query, $search) {
        if ($search) {
            $query->where('id', 'LIKE', '%' . $search . '%');
        }
    }
}
