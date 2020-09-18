<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginDetails extends Model
{
    protected $fillable = ['user_id', 'last_login_at', 'last_login_ip', 'last_login_location'];


    public function getDateAttribute() {
        $str = $this->last_login_at;
        $date = strtotime($str);
        return date('d/M/Y h:i:s', $date);
    }

    public function getLocationAttribute() {
        $ip = $this->last_login_ip;

        $data = \Location::get($ip);

        return $data->countryName .", ". $data->cityName;
    }
}
