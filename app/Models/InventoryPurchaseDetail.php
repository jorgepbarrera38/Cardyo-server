<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_purchase_id',
        'inventory_product_id',
        'quantity',
        'price_unit',
        'price_total',
    ];

    public function product () {
        return $this->belongsTo('App\Models\InventoryProduct', 'inventory_product_id');
    }

    public function purchaseReturnDetails () {
        return $this->hasMany('App\Models\InventoryPurchaseReturnDetail', 'inv_purchase_detail_id');
    }
}
