<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\OauthAccessToken;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->first_name . ' ' . $user->last_name;

            return $this->sendResponse($success, 'User register successfully.');
        }catch (\Exception $e){
            return $this->sendError('exception', ['error'=>$e->getMessage()]);
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            if (Auth::attempt($this->credentials($request))) {
                $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->accessToken;
                $success['name'] = $user->first_name . ' ' . $user->last_name;

                return $this->sendResponse($success, 'User login successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        }catch (\Exception $e){
            return $this->sendError('exception', ['error'=>$e->getMessage()]);
        }
    }

    /**
     * login credentials check
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if(is_numeric($request->get('email'))){
            return ['phone'=>$request->get('email'),'password'=>$request->get('password')];
        }
        elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->get('email'), 'password'=>$request->get('password')];
        }
        return ['username' => $request->get('email'), 'password'=>$request->get('password')];
    }
    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        try {
            if (Auth::check()) {
                OauthAccessToken::find(Auth::user()->token()->id)->delete();
                return $this->sendResponse([], 'User logout successfully.');
            }else{
                return $this->sendError('Unauthorised.', ['error'=>'Please login first.']);
            }
        }catch (\Exception $e){
            return $this->sendError('exception', ['error'=>$e->getMessage()]);
        }
    }

    /**
     * logout from all devices api
     *
     * @return \Illuminate\Http\Response
     */
    public function allLogout()
    {
        try {
            if (Auth::check()) {
                Auth::user()->AauthAcessToken()->delete();
                return $this->sendResponse([], 'All devices logged out successfully.');
            }else{
                return $this->sendError('Unauthorised.', ['error'=>'Please login first.']);
            }
        }catch (\Exception $e){
            return $this->sendError('exception', ['error'=>$e->getMessage()]);
        }
    }
    public function test(){
        return $this->sendResponse([], 'User login successfully.');
    }
}
