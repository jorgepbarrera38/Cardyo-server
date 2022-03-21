<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBodyMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'height',
        'weight',
        'biceps',
        'forearms',
        'shoulders',
        'chest',
        'waist_width',
        'width_at_navel_level',
        'buttocks_or_hip',
        'thigh',
        'calves',
    ];
}
