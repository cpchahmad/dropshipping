<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Stevebauman\Location\Location;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','last_login_at', 'last_login_ip', 'last_login_location',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRoleAttribute() {
        $role_array = $this->getRoleNames();
        if($role_array[0] == 'shipping_team') {
            return 'Shipping Team';
        }
        else if($role_array[0] == 'outsource_team'){
            return 'Source Team';
        }
        else {
            return 'Admin';
        }

    }

    public function getRoleNameAttribute() {
        return $this->roles->first()->name === "shipping_team" ? 'Shipping Team' : 'Source Team';
    }

    public function getProductCountAttribute() {
        return Product::where('outsource_id', $this->id)->count();
    }

    public function getApproveProductCountAttribute() {
        return Product::where('outsource_id', $this->id)->where('approved', 1)->count();
    }

    public function getUnapproveProductCountAttribute() {
        return Product::where('outsource_id', $this->id)->where('approved', 2)->count();
    }


    public function getDateAttribute() {
        $str = $this->last_login_at;
        $date = strtotime($str);
        return date('d,M,Y h:i:s', $date);
    }

    public function getLocationAttribute() {
        $ip = $this->last_login_ip;

        $data = \Location::get($ip);

        return $data->countryName .", ". $data->cityName;
    }

    public function getCreateAttribute() {
        $str = $this->created_at;
        $date = strtotime($str);
        return date('d,M,Y', $date);
    }

}
