<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function setPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required|exists:users,confirm_token',
            'password' => 'required'
        ]);
        $validator->validate();

        $user = User::where('confirm_token',$request->token)->first();
        $user->password = bcrypt($request->password);
        $user->confirm_token = null;
        $user->save();
        $token =  $user->createToken(env('APP_NAME'))->accessToken;
        $success['token'] = $token;
        $success['user'] = $user;
        return $this->successApiResponse('Login success', $success);

    }
    public function login(Request $request){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $token =  $user->createToken(env('APP_NAME'))->accessToken;
            $success['token'] = $token;
            $success['user'] = $user;
            return $this->successApiResponse('Login success', $success);
        }
        else{
            return $this->unauthorisedApiResponse();
        }
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'same:password',
        ]);
        $validator->validate();
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken(env('APP_NAME'))-> accessToken;
        $success['user'] =  $user;
        return $this->successApiResponse('Registration success', $success);
    }


    public function socialLogin(Request $request, $provider){
        switch ($provider){
            case 'google': return $this->googleLogin($request);
            case 'telegram': return $this->telegramLogin($request);
        }
    }


    public function googleLogin(Request $request){
        $tokenUser = Socialite::driver('google')->userFromToken($request->token);

        if(!$tokenUser){
            return $this->internalErrorApiResponse('Что то пошло не так');
        }
        $user = User::where('email',$tokenUser->email)->first();
        if(!$user){
            $user = User::create([
                'avatar_url' => $tokenUser->avatar_original,
                'first_name' => $tokenUser->user['name']['givenName'],
                'last_name' => $tokenUser->user['name']['familyName'],
                'email' => $tokenUser->email
            ]);
            $success['token'] =  $user->createToken(env('APP_NAME'))-> accessToken;
            $success['user'] =  $user;
        } else {
            $user->update([
                'avatar_url' => $tokenUser->avatar_original,
                'first_name' => $tokenUser->user['name']['givenName'],
                'last_name' => $tokenUser->user['name']['familyName'],
                'email' => $tokenUser->email
            ]);
            $success['token'] =  $user->createToken(env('APP_NAME'))-> accessToken;
            $success['user'] =  $user;
        }

        return $this->successApiResponse('Login success', $success);
    }

    public function telegramLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        $validator->validate();

        $user = User::where('telegram_id',$request->id)->first();
        $data = $request->only("id","first_name","last_name","photo_url");
        if(!$user){
            $user = User::create([
                'avatar_url' => isset($data['photo_url'])? $data["photo_url"] : null,
                'first_name' => isset($data['first_name'])? $data["first_name"] : null,
                'last_name' => isset($data['last_name'])? $data["last_name"] : null,
                'telegram_id' => isset($data['id'])? $data["id"] : null,
            ]);
            $success['token'] =  $user->createToken(env('APP_NAME'))-> accessToken;
            $success['user'] =  $user;
        } else {
            $user->update([
                'avatar_url' => isset($data['photo_url'])? $data["photo_url"] : null,
                'first_name' => isset($data['first_name'])? $data["first_name"] : null,
                'last_name' => isset($data['last_name'])? $data["last_name"] : null,
                'telegram_id' => isset($data['id'])? $data["id"] : null,
            ]);
            $success['token'] =  $user->createToken(env('APP_NAME'))-> accessToken;
            $success['user'] =  $user;
        }

        return $this->successApiResponse('Login success', $success);
    }


    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();
        $user->delete();
        return $this->successApiResponse();
    }

}
