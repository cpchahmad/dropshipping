<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $fillable = [
        'tracking_number',
        'tracking_url',
        'tracking_company',
        'location_id',
    ];
}
