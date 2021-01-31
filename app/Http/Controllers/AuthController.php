<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Library\Jwt;

use Symfony\Component\HttpFoundation\Cookie;
//use Illuminate\Support\Facades\Cookie;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login', 'generate']]);
        $this->middleware('auth:api', ['except' => ['login', 'logout', 'generate']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::findOrFail(auth()->user()->getAuthIdentifier());

        $payload = [
            'userId' => $user->id,
            'roleId' => $user->role_id,
        ];

        return $this->responseWithToken($payload);
    }

    public function authorized(){
        $user = User::findOrFail(auth()->user()->getAuthIdentifier());
        $payload = [
            'userId' => $user->id,
            'roleId' => $user->role_id,
            'authMenu' => [
                [
                    'name' => 'Dashboard',
                    'path' => '/dashboard',
                    'icon' => ['fas', 'chart-line'],
                ],
                [
                    'name' => 'Mandor',
                    'path' => '/mandor',
                    'icon' => ['fas', 'user'],
                ],
                [
                    'name' => 'Anggota',
                    'path' => '/anggota',
                    'icon' => ['fas', 'users'],
                ],
            ]
        ];

        return $this->responseWithoutToken($payload);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ])->withCookie(Cookie::create('Authorization', ''));
    }

    public function generate()
    {
        $users = new User;
        $users->email = 'jihanlugas2@gmail.com';
        $users->name = 'Jihan Lugas';
        $users->password = Hash::make('123456');
        $users->gender = 'MALE';
        $users->role_id = 1;
        $users->save();

        return 'success';
    }
}
