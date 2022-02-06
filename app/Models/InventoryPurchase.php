<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_provider_id',
        'date_bill',
        'bill',
        'attachment',
        'observations',
        'total',
    ];

    public function purchaseDetails () {
        return $this->hasMany('App\Models\InventoryPurchaseDetail', 'inventory_purchase_id');
    }

    public function provider () {
        return $this->hasMany('App\Models\InventoryProvider', 'inventory_provider_id');
    }
}
