<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPurchaseReturnDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inv_purchase_detail_id',
        'quantity',
    ];

    public function inventoryPurchaseDetail () {
        return $this->belongsTo('App\Models\InventoryPurchaseDetail', 'inv_purchase_detail_id');
    }

    public function inventoryPurchaseReturn () {
        return $this->belongsTo('App\Models\InventoryPurchaseReturn', 'inv_purchase_return_id');
    }
}
