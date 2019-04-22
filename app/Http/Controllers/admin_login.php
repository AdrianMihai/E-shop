<?php
	namespace App\Http\Controllers;

	use DB;
	use App\User;
	use Illuminate\Http\Request;

	class AdminsController extends Controller{

		public function login(Request $request){
			$data = $request->all();
			$users  = DB::table('employees')->get();

			return $users;
		}
	}
?>