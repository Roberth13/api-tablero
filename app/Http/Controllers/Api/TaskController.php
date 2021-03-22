<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Historial de tareas por proyecto.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHistorial(Request $request, $id)
    {
        $columns = [
            'ut.id','u.name as userNames', 'u.email as userEmail','p.name as projectName', 't.title', 's.name as status', 'ut.created_at'
        ];
        $task = DB::table('user_tasks AS ut')
                        ->select($columns)
                        ->join('tasks AS t', 'ut.task_id', '=', 't.id')
                        ->join('users AS u', 'ut.user_id', '=', 'u.id')
                        ->join('projects AS p', 't.project_id', '=', 'p.id')
                        ->join('states AS s', 'ut.state_id', '=', 's.id')
                        ->where('p.id', $id)
                        ->orderBy('ut.id', 'ASC')
                        ->get();

        return response([ 'data' =>  $task, 'success' => 1], 200);
    }

    /**
     * Historial de tareas por proyecto.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMyTasks(Request $request, $id)
    {
        $user = $request->user('api');
        $columns = [
            't.id', 't.title', 's.name as status', 't.updated_at', 's.id as stateId'
        ];
        $task = DB::table('tasks AS t')
                        ->select($columns)
                        ->join('projects AS p', 't.project_id', '=', 'p.id')
                        ->join('states AS s', 't.state_id', '=', 's.id')
                        ->leftjoin('user_tasks as ut', 't.id', 'ut.task_id')
                        ->where('p.id', $id)
                        ->where('ut.user_id', $user->id)
                        ->distinct()
                        ->get();

        return response([ 'data' =>  $task, 'success' => 1], 200);
    }

    /**
     * Create task.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $user = $request->user('api');
        $validator = Validator::make($data, [
            'project_id' => 'required',
            'state_id' => 'required|exists:states,id',
            'title'    => 'required|string',
            'comment'  => 'nullable|string',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $columns = [
            'project_id' => $data['project_id'],
            'state_id'   => $data['state_id'],
            'title'      => $data['title'],
            'comment'    => $data['comment'],
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $task = DB::table('tasks')->insertGetId($columns);

        if($task > 0){
            $columns = [
                'task_id' => $task,
                'user_id' => $user->id,
                'state_id' => $data['state_id'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
            DB::table('user_tasks')->insert($columns);
        }

        $columns = [
            'ut.id', 't.title', 's.name as status', 'ut.created_at'
        ];
        $task_data = DB::table('user_tasks AS ut')
                        ->select($columns)
                        ->join('tasks AS t', 'ut.task_id', '=', 't.id')
                        ->join('users AS u', 'ut.user_id', '=', 'u.id')
                        ->join('projects AS p', 't.project_id', '=', 'p.id')
                        ->join('states AS s', 't.state_id', '=', 's.id')
                        ->where('t.id', $task)
                        ->get();

        return response([ 'data' => $task_data, 'message' => 'Tarea creada con éxito', 'success' => 1], 200); 
        
    }

    /**
     * Delete task.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $user = $request->user('api');
        DB::table('user_tasks AS ut')->where('task_id',$id)->where('user_id', $user->id)->delete();
        DB::table('tasks')->where('id', $id)->delete();
        return response([ 'data' => $task_data, 'message' => 'Tarea eliminada con éxito', 'success' => 1], 200); 
    }

    /**
     * Update Task
     */

    public function update(Request $request){
        $data = $request->all();
        $user = $request->user('api');
        $validator = Validator::make($data, [
            'id'       => 'required|exists:tasks,id',
            'state_id' => 'required|exists:states,id',
            'title'    => 'required|string',
            'comment'  => 'nullable|string',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $columns = [
            'state_id'   => $data['state_id'],
            'title'      => $data['title'],
            'comment'    => $data['comment'],
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('tasks')->where('id', $data['id'])->update($columns);

        $columns = [
            'task_id' => $data['id'],
            'user_id' => $user->id,
            'state_id' => $data['state_id'],
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];
        DB::table('user_tasks')->insert($columns);

        return response(['message' => 'Tarea actualizada con éxito', 'success' => 1], 200); 
    }
}
