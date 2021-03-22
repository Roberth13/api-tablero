<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the companies.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $companies = Company::all();

        return response([ 'data' =>  $companies, 'success' => 1], 200);
    }
}
