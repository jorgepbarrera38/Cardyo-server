<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'identification',
        'names',
        'lastname',
        'full_names',
        'sex',
        'date_of_birth',
        'address',
        'email',
        'phonenumber',
        'photo',
        'user_create_id'
    ];

    public function memberships () {
        return $this->hasMany('App\Models\ClientMembership');
    }

    public function bodyMeasurements () {
        return $this->hasMany('App\Models\ClientBodyMeasurement');
    }

    public function sales () {
        return $this->hasMany('App\Models\Sale');
    }

    public function scopeSearch ($query, $search) {
        if ($search) {
            $query->where('identification', 'LIKE', '%' . $search . '%')
                    ->orWhere('full_names', 'LIKE', '%' . $search . '%');
        }
    }
}
