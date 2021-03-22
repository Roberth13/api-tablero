<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $data = $request->all();

        $validatedData = Validator::make($data, [
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed',
            'company_id' => 'required|exists:companies,id'
        ]);

        $data["password"] = Hash::make($request->password);
        
        if($validatedData->fails()){
            return response(['error' => $validatedData->errors(), 'Validation Error']);
        }

        $user = User::create($data);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken], 201);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'El usuario no existe'], 400);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }

    public function getCurrent(Request $request){
        $user = $request->user('api');
        $columns = [
            'c.id','c.names AS company', 'c.address','c.nit', 'c.phone', 'c.email AS emailC','u.name','u.email','u.created_at'
        ];

        $user = DB::table('users AS u')
                        ->select($columns)
                        ->join('companies AS c', 'u.company_id', '=', 'c.id')
                        ->where('u.id', $user->id)
                        ->first();

        return response([ 'data' =>  $user, 'success' => 1], 200);
    }
}