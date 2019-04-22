<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    //
    protected $table = 'logs' ;

    public $timestamps = false;

    public function insert($text, $category, $userId){
    	$this->text = $text;
    	$this->employee = $userId;
    	$this->created_at = time();
    	$this->category = $category;
    	
    	$this->save();
    }
}
