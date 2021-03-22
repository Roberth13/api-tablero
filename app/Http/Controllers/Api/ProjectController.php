<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Project;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = $request->user('api');

        $columns = [
            'p.id','p.name as projectName', 'p.company_id','c.names as companyName', 'c.address', 'c.nit','c.phone','c.email'
        ];
        $projects = DB::table('projects AS p')
                        ->select($columns)
                        ->join('companies AS c', 'p.company_id', '=', 'c.id')
                        ->where('p.company_id', '=', $user->company_id)
                        ->get();

        return response([ 'data' =>  $projects, 'success' => 1], 200);
    }

    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, $id)
    {
        $user = $request->user('api');

        $columns = [
            'p.id','p.name as projectName', 'p.company_id','c.names as companyName', 'c.address', 'c.nit','c.phone','c.email'
        ];

        $projects = DB::table('projects AS p')
                        ->select($columns)
                        ->join('companies AS c', 'p.company_id', '=', 'c.id')
                        ->where('p.id', $id)
                        ->where('c.id', $user->company_id)
                        ->first();

        return response([ 'data' =>  $projects, 'success' => 1], 200);
    }

    /**
     * Create project.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $user = $request->user('api');

        if($user->company_id == $request['company_id']){
            $validator = Validator::make($data, [
                'name' => 'required',
                'company_id' => 'required|exists:companies,id'
            ]);
    
            if($validator->fails()){
                return response(['error' => $validator->errors(), 'Validation Error']);
            }
    
            $project = Project::create($data);
    
            return response([ 'data' => $project, 'message' => 'Proyecto creado con éxito'], 200);
        }else{
            return response([ 'message' => 'No puede crear un proyecto para esa compañia: '], 200);
        }

        
    }
}
