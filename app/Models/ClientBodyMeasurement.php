<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBodyMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'date',
        'body_measurement_id',
        'value',
    ];

    public function bodyMeasurement () {
        return $this->belongsTo('App\Models\BodyMeasurement');
    }
}
