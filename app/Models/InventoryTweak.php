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
    ];

    public function tweakDetails () {
        return $this->hasMany('App\Models\InventoryTweakDetail');
    }
}
