<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Symfony\Component\Console\Input\Input;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Login username to be used by the controller.
     *
     * @var string
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        $user_name = request()->input('email');
        if(is_numeric($user_name)) {
            return 'phone';
        } else if (filter_var($user_name, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        } else {
            return 'username';
        }
    }

}
