<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SaveUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;


class UserController extends Controller
{
    /**
     * @var \App\Repositories\User\UserContract
     */
    private $users;


    public function index(Request $request)
    {
        $perPage = $request->limit ? (int)$request->limit : 20;

        $query = User::query();

        $result = $query->orderBy('id', 'desc')
            ->paginate($perPage);

        return response()->json(['success' => $result])->setStatusCode(Response::HTTP_OK);
    }

    public function detail($id)
    {
        $user = User::findOrFail($id);

        return response()->json(['success' => $user])->setStatusCode(Response::HTTP_OK);
    }

    public function create(SaveUserRequest $request)
    {
        $username = $request->username;
        $email = $request->email;
        $email_verified_at = $request->email_verified_at;
        $password = $request->password;
        $remember_token = $request->remember_token;

        $user = User::create([
            'username' => $username,
            'email' => $email,
            'email_verified_at' => $email_verified_at,
            'password' => bcrypt($password),
            'remember_token' => $remember_token,
        ]);

        return response()->json(['success' => $user])->setStatusCode(Response::HTTP_OK);
    }

    public function update($id, UpdateUserRequest $request)
    {
        $username = $request->username;
        $email = $request->email;
        $email_verified_at = $request->email_verified_at;
        $password = $request->password;
        $remember_token = $request->remember_token;

        $user = User::findOrFail($id);

        $user->update([
            'username' => $username,
            'email' => $email,
            'email_verified_at' => $email_verified_at,
            'password' => $password,
            'remember_token' => $remember_token,
        ]);

        return response()->json(['success' => $user])->setStatusCode(Response::HTTP_OK);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['success' => $user])->setStatusCode(Response::HTTP_OK);
    }
}
