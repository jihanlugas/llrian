<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Library\Jwt;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login']]);
        $this->middleware('auth:api', ['except' => ['login', 'logout']]);
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

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function authorized(){
        $user = User::findOrFail(auth()->user()->getAuthIdentifier());
        $payload = [
            'userId' => $user->id,
            'roleId' => $user->role_id,
            'authMenu' => [
                [
                    'name' => 'Dashboard',
                    'path' => '/dashboard'
                ],
                [
                    'name' => 'Mandor',
                    'path' => '/mandor'
                ],
                [
                    'name' => 'Anggota',
                    'path' => '/anggota'
                ],
            ]
        ];

        return $this->responseWithoutToken($payload);
    }

    public function generate()
    {
        $users = new User;
        $users->email = 'jihanlugas2@gmail.com';
        $users->name = 'Jihan Lugas';
        $users->password = Hash::make('123456');
        $users->gender = 'Laki Laki';
        $users->role_id = 1;
        $users->save();

        return 'success';
    }
}
