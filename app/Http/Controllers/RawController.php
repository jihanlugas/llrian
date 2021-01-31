<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Library\Jwt;

use Symfony\Component\HttpFoundation\Cookie;
//use Illuminate\Support\Facades\Cookie;


class RawController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt');
        $this->middleware('auth:api');
    }

    public function mandor()
    {
        $query = User::query();
        $query->where('role_id','=', User::ROLE_MANDOR);

        $listdata = $query->get();

        $payload['list'] = [];
        $payload['idFieldName'] = 'userId';
        foreach ($listdata as $key => $data){
            $payload['list'][] = [
                'userId' => $data->id,
                'name' => $data->name,
                'email' => $data->email,
                'gender' => $data->gender,
                'roleId' => $data->role_id,
            ];
        }
        return $this->responseWithoutToken($payload);
    }
}
