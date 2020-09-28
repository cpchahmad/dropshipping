<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Log;
use App\LoginDetails;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Stevebauman\Location\Location;
use \Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    function authenticated(Request $request, $user)
    {
//        $data = \Location::get($request->ip());

        LoginDetails::create([
           'user_id' => $user->id,
           'last_login_at' => Carbon::now()->toDateTimeString(),
           'last_login_ip' => $request->getClientIp(),
        ]);

        Log::create([
           'user_id' => $user->id,
           'attempt_time' => Carbon::now()->toDateTimeString(),
           'attempt_location_ip' => $request->getClientIp(),
           'type' => 'Log in',
        ]);

        $user->last_login_at = Carbon::now()->toDateTimeString();
        $user->last_login_ip = $request->getClientIp();
        $user->save();
    }
}
