<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTweakDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_tweak_id',
        'inventory_product_id',
        'quantity',
    ];

    public function product () {
        return $this->belongsTo('App\Models\InventoryProduct', 'inventory_product_id');
    }
}
