<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'price_unit',
        'quantity',
        'price_total',
    ];

    public function product () {
        return $this->belongsTo('App\Models\InventoryProduct');
    }
}
