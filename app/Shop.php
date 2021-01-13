<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{

    protected $fillable = [
        'shop_domain',
        'api_key',
        'api_secret',
        'api_version',
        'shop_type',
        'api_password'
    ];
}
