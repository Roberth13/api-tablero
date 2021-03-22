<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\State;

class StateController extends Controller
{
    /**
     * Display a listing of the companies.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $states = State::all();

        return response([ 'data' =>  $states, 'success' => 1], 200);
    }
}
