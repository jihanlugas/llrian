<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Hash;
use App\Library\Jwt;

use Symfony\Component\HttpFoundation\Cookie;
//use Illuminate\Support\Facades\Cookie;


class PageController extends Controller
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
        $page = request('page');        // number
        $sort = request('sort');        // Array
        $filters = request('filters');    // Array

        $query = User::query();
        $query->where('role_id','=', User::ROLE_MANDOR);
        $allData = $query->paginate(5, '*', 'page', $page);

        $payload = [];

        $payload['page'] = $allData->currentPage();
        $payload['dataPerPage'] = $allData->perPage();
        $payload['totalData'] = $allData->total();
        $payload['totalPage'] = ceil($payload['totalData'] / $payload['dataPerPage']);
        $payload['list'] = [];
        foreach ($allData as $key => $data){
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

    public function anggota()
    {
        $page = request('page');        // number
        $sort = request('sort');        // Array
        $filters = request('filters');    // Array

        $query = User::query();
        $query->where('role_id','=', User::ROLE_ANGGOTA);
        $allData = $query->paginate(5, '*', 'page', $page);

        $payload = [];

        $payload['page'] = $allData->currentPage();
        $payload['dataPerPage'] = $allData->perPage();
        $payload['totalData'] = $allData->total();
        $payload['totalPage'] = ceil($payload['totalData'] / $payload['dataPerPage']);
        $payload['list'] = [];
        foreach ($allData as $key => $data){
            $payload['list'][] = [
                'userId' => $data->id,
                'name' => $data->name,
                'email' => $data->email,
                'gender' => $data->gender,
                'roleId' => $data->role_id,
                'mandorId' => $data->mandor_id,
                'mandor' => $data->mandor->name,
            ];
        }
        return $this->responseWithoutToken($payload);
    }

    public function project()
    {
        $page = request('page');        // number
        $sort = request('sort');        // Array
        $filters = request('filters');    // Array

        $query = Project::query();
        $allData = $query->paginate(5, '*', 'page', $page);

        $payload = [];

        $payload['page'] = $allData->currentPage();
        $payload['dataPerPage'] = $allData->perPage();
        $payload['totalData'] = $allData->total();
        $payload['totalPage'] = ceil($payload['totalData'] / $payload['dataPerPage']);
        $payload['list'] = [];
        foreach ($allData as $key => $data){
            $payload['list'][] = [
                'projectId' => $data->id,
                'name' => $data->name,
                'address' => $data->address,
                'userId' => $data->user_id,
                'mandor' => $data->user->name,
            ];
        }
        return $this->responseWithoutToken($payload);
    }
}
