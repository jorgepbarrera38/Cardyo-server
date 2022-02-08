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
}
