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
}
