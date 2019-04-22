<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Employee;

use App\Log;

use Auth;

use DB;

use Hash;

function clean_input($data){ // remove unecessary spaces, backslashes, convert HTML characters
	$data = trim($data);
	$data = stripslashes($data);
	//$data = htmlspecialchars($data);
	return $data;
}

function var_dump_ret($mixed = null) {
		  ob_start();
		  var_dump($mixed);
		  $content = ob_get_contents();
		  ob_end_clean();
		  return $content;
	}

class EmployeesController extends Controller
{
    //
    public function getPositions(Request $request){
    	$positions = DB::table('positions')->get();

    	return json_encode($positions);
    }

    public function store(Request $request){
    	if($request->isMethod('post')){
    		
    		$userPosition = Auth::guard('admin')->user()->position;

    		if($userPosition == 0 || $userPosition == 1){
    			$data = $request->all();

    			//return json_encode( $data['first_name'] );

    			$employeeData = array(
    				'first_name' => clean_input($data['first_name']),
    				'last_name' => clean_input( $data['last_name']),
    				'email' => clean_input( $data['email'] ),
    				'phone_number' => clean_input($data['phone_number']),
    				'cnp' => clean_input($data['cnp']),
    				'position' => $data['position'],
    				'password' => Hash::make( clean_input($data['password']) ),
    				'birthdate' => strtotime($data['birthdate']	),
    				'created_at' => time(),
    				'added_by' => Auth::guard('admin')->user()->id,

    				 ); 

    			if($request->hasFile('image')){
    				$imageName = $employeeData['cnp'] . str_replace(' ', '_', $request->file('image')->getClientOriginalName());
    				$request->file('image')->move( 'resources/employeesImages/', $imageName );

    				$imagePath = 'resources/employeesImages/' . $imageName;
    			}
    			else
    				$imagePath = 'resources/employeesImages/poza_profil.png';

    			$employeeData['image_path'] = $imagePath;
    			$employee = Employee::create( $employeeData);

    			//write the log
				$log = new Log;
				$logText = 'added the employee ' . $employee['first_name'] . ' ' . $employee['last_name'] 
							. '( ' . $employee['cnp'] . ' )' ;
				$log->insert($logText, 'employees', Auth::guard('admin')->user()->id);

    			return json_encode($employee);
    		}
    		else{
                //write the log
                $log = new Log;
                $logText = 'tried to add a new employee ';
                $log->insert($logText, 'employees', Auth::guard('admin')->user()->id);
            }
    			return json_encode('unauthorized');
    	}	
    }

    public function getAll(){
    	$employees = Employee::all();

    	return json_encode($employees);
    }
}
