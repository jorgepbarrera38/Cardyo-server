<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPurchaseReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_purchase_id',
        'observations',
    ];

    public function purchaseReturnDetails () {
        return $this->hasMany('App\Models\InventoryPurchaseReturnDetail', 'inv_purchase_return_id');
    }

    public function purchase () {
        return $this->belongsTo('App\Models\InventoryPurchase', 'inventory_purchase_id');
    }
}