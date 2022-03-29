<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientMembershipAssistance extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_membership_id',
        'turn_id',
        'date',
    ];

    public function clientMembership () {
        return $this->belongsTo('App\Models\ClientMembership');
    }

    public function turn () {
        return $this->belongsTo('App\Models\Turn');
    }
}
