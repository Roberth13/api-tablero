<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['names', 'address', 'nit', 'phone', 'email'];
}
