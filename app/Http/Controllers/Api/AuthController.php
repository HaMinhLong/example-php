<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SaveUserRequest;
use App\Models\User;

class AuthController extends Controller
{
    protected $username;


    public function __construct()
    {
        $this->username = $this->findUsername();
    }

    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'account' => 'required',
            'password' => 'required'
        ]);

        $loginType = filter_var($request->input('account'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $request->merge([
            $loginType => $request->input('account')
        ]);


        $credentials = $request->only($loginType, 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorised.'], Response::HTTP_EXPECTATION_FAILED);
        } else {
            auth()->user()->tokens->each(function ($token) {
                $token->delete();
            });
            $success['token'] = auth()->user()->createToken('authToken')->accessToken;
            $success['user'] = auth()->user();
            return response()->json(['success' => $success])->setStatusCode(Response::HTTP_OK);
        }
    }

    public function details()
    {
        $user = Auth::user();

        return response()->json(['success' => $user])->setStatusCode(Response::HTTP_OK);
    }

    public function register(Request $request)
    {
        $username = $request->name;
        $email = $request->email;
        $password = $request->password;

        $user = User::create([
            'username' => $username,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        return response()->json(['success' => $user])->setStatusCode(Response::HTTP_OK);
    }
}