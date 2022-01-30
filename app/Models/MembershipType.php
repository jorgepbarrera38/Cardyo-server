<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price',
        'quantity',
    ];

    public function memberships () {
        return $this->hasMany('App\Models\ClientMembership');
    }
}
