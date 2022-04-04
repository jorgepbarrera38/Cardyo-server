<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'total',
        'status',
        'user_create_id',
        'user_delete_id',
    ];

    public function saleDetails () {
        return $this->hasMany('App\Models\SaleDetail');
    }

    public function client () {
        return $this->belongsTo('App\Models\Client');
    }

    public function scopeSearch ($query, $search) {
        if ($search) {
            $query->where('id', 'LIKE', '%' . $search . '%');
        }
    }
}
