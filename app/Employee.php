<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $table = 'employees';

    protected $guarded = ['remember_token'];
    
    public $timestamps = false;
}
