<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Library\Jwt;

use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\Providers\Auth;


class AnggotaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cors');
        $this->middleware('jwt');
        $this->middleware('auth:api');
    }

    public function form(){
        $id = request('id');
        $user = User::find($id);
        $payload = [];
        if($user) {
            $payload['form'] = [
                'userId' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $user->gender,
                'roleId' => $user->role_id,
                'mandorId' => $user->mandor_id,
            ];
        }else{
            $payload['form'] = [
                'userId' => 0,
                'name' => "",
                'email' => "",
                'gender' => "",
                'roleId' => 0,
                'mandorId' => 0
            ];
        }

        return $this->responseWithoutToken($payload);
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->role_id = User::ROLE_ANGGOTA;
            $user->password = Hash::make('123456');
            $user->mandor_id = $this->isAdmin() ? $request->mandorId : auth()->user()->getAuthIdentifier();
            $user->save();

            DB::commit();
            return $this->responseWithoutToken();
        } catch (Throwable $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function update(Request $request){
        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->role_id = User::ROLE_ANGGOTA;
            $user->mandor_id = $this->isAdmin() ? $request->mandorId : auth()->user()->getAuthIdentifier();
            $user->save();

            DB::commit();
            return $this->responseWithoutToken();
        } catch (Throwable $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function destroy(Request $request){
        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->id);
            $user->delete();

            DB::commit();
            return $this->responseWithoutToken();
        } catch (Throwable $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
