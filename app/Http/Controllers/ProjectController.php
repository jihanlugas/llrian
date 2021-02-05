<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Hash;
use App\Library\Jwt;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
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
        $project = Project::find($id);
        $payload = [];
        if($project) {
            $payload['form'] = [
                'projectId' => $project->id,
                'name' => $project->name,
                'address' => $project->address,
                'userId' => $project->user_id,
            ];
        }else{
            $payload['form'] = [
                'projectId' => 0,
                'name' => "",
                'address' => "",
                'userId' => 0,
            ];
        }

        return $this->responseWithoutToken($payload);
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $project = new Project();
            $project->name = $request->name;
            $project->address = $request->address;
            $project->user_id = $request->userId;
            $project->save();

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
            $project = Project::findOrFail($request->projectId);
            $project->name = $request->name;
            $project->address = $request->address;
            $project->user_id = $request->userId;
            $project->save();

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
            $project = Project::findOrFail($request->id);
            $project->delete();

            DB::commit();
            return $this->responseWithoutToken();
        } catch (Throwable $e) {
            DB::rollBack();
            dd($e);
        }
    }

//    private function saveData(){
//
//    }

}
