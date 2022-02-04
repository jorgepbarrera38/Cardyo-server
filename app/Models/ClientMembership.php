<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientMembership extends Model
{
    use HasFactory;
    protected $fillable = [
        'turn_id',
        'membership_type_id',
        'price',
        'quantity',
        'total_price',
        'date_start',
        'date_finish',
        'observations',
    ];

    public function membershipType () {
        return $this->belongsTo('App\Models\MembershipType');
    }

    public function turn () {
        return $this->belongsTo('App\Models\Turn');
    }

    public function clientMembershipAssistances () {
        return $this->hasMany('App\Models\ClientMembershipAssistance');
    }
}
