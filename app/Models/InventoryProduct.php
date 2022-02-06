<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'photo',
        'price',
        'stock',
        'stock_min',
        'stock_max',
        'inventory_category_id',
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\InventoryCategory', 'inventory_category_id');
    }
}
