<?php
	namespace App\Http\Controllers;

	use Hash;

	use Auth;

	use DB;

	use Carbon\Carbon;

	use App\Log;

	use Illuminate\Http\Request;

	use Illuminate\Http\UploadedFile;

	function check_type($extensions, $type){
		for ($i = 0; $i< count($extensions); $i++) 
			if($type == 'image/' . $extensions[$i])
				return 1;
		return 0;
	}

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

	Class AdminsController extends Controller{

		public function login (Request $request){
			
			if($request->isMethod('post')){
				$data = $request->all();

				//Check if the user exists
				if(empty(DB::table('employees')->where('Email', $data['email'])->value('Email')) )  {
					return "email";
				}

				//If it exists check if the password is correct
				$password = DB::table('employees')->where('Email', $data['email'])->value('Password');

				if(Hash::check($data['password'], $password)){

					Auth::guard('admin')->attempt(['email'=> $data['email'], 'password' => $data['password'] ]
													,json_decode($data['remember']) );
					return 'ok';
				}
				else{
					return "password";
				}
				//return "nope";
			}
			
		}

		public function logout(Request $request){

			Auth::guard('admin')->logout();
			return redirect('admin');
		}

		public function load_products(Request $request){
			$products = DB::table('products')->where('displayed', true)->get();
			/*$categories = array();
			DB::table('categories')->where('category', 'Films')->update(['sub_category' => json_encode($categories)]);*/

			for($i = 0; $i < count($products); $i++){
				$products[$i]->addedby = DB::table('employees')->where('id', $products[$i]->addedby  )->value('first_name')
											. ' ' . DB::table('employees')->where('id', $products[$i]->addedby )->value('last_name');
			}
			return response()->json($products);
		}

		public function get_categories(Request $request){
			$categories = DB::table('categories')->get();
			return $categories;
		}

		//get value of q parameter from the URL when searching for a product
		//returns the products' view
		public function searchProducts(Request $request){
			$q = $request->q;

			return view('admin_layouts.products')->with('q', $q);
		}
		
		//get the shipping price and the price from which the shipping starts to become free
		public function getShippingSettings(Request $request){
			$shippingData = json_decode(DB::table('settings')->where('related_to', 'shipping')->value('information'));
			$shippingData = [
				'price' => (int)$shippingData->price,
				'free_from' => (int)$shippingData->free_from
			];

			return view('admin_layouts.settings')->with($shippingData);
		}

		public function saveShippingSettings(Request $request){
			$shippingData = [
				'price' => (int)$request->price,
				'free_from' => (int)$request->free_from
			];

			DB::table('settings')->where('related_to', 'shipping')->update(['information' => json_encode($shippingData)]);

			return redirect('./admin/settings');
		}
		
		//check if the image is small enough and has the accepted extensions
		public function image_process(Request $request){ 
			$image = $request->file('image');
			$extensions = array('jpg', 'jpeg', 'gif', 'png');//accepted extensions for images

			if($image->isValid())
				if(!check_type($extensions, $image->getClientMimeType()) || ($image->getClientSize()/ (1024*1024) > 10))
					return json_encode(false);
				else
					return json_encode(true);
		}

		//function that inserts a new product into the database
		public function add_product(Request $request){
			$data = $request->all();

			if( !empty(DB::table('products')->where('id', $data['id'])->value('id') )   ){
				return json_encode(false);
			}
			else{
				if( $request->hasFile('image') ){
					$image_path = 'resources/productsImages/' . str_replace(' ', '_', $data['id']) 
										. $request->file('image')->getClientOriginalName();
					$request->file('image')->move('resources/productsImages/', str_replace(' ', '_', $data['id']) . 
												$request->file('image')->getClientOriginalName() );
				}
				else{
					$image_path = 'resources/productsImages/basic.jpg';
				}
				$dataInsert = array(
					'id' => clean_input( $data['id'] ),
					'product_name' => clean_input($data['name']),
					'manufacturer' => clean_input($data['manufacturer']),
					'price' => round( clean_input($data['price']), 2 ),
					'discounted_price'=> round ($data['discount'] != 0 ? $data['price'] * (100 - $data['discount'])/100 : 0 , 2),
					'discount' => isset($data['discount']) ? $data['discount'] : 0,
					'quantity' => $data['quantity'],
					'category' => $data['category'],
					'sub_category' => $data['subCategory'],
					'image_path' =>  $image_path,
					'addedon' => time(),
					'addedby' => Auth::guard('admin')->user()->id
				);
				DB::table('products')->insert($dataInsert);

				//write the log
				$log = new Log;
				$logText = 'added a new product with the id ' . $dataInsert['id'];
				$log->insert($logText, 'products', $dataInsert['addedby']);

				$dataInsert['addedby'] = Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name;


				return json_encode($dataInsert);
			}
			
			//return var_dump_ret($request->file('image')->getClientOriginalName());	
		}

		//function that updates data for a product
		public function updateProduct(Request $request){
			if($request->isMethod('post')){
				$data = $request->all();
				$position = Auth::guard('admin')->user()->position;

				if(empty(DB::table('products')->where('id', $data['id'])->value('id')) )//if there is no product with the given id
					return json_encode(false);
				else if($position == '0' || $position == '1')
				{
					$currentImagePath = DB::table('products')->where('id', $data['id'])->value('image_path');
					if($request->hasFile('image')){
						if($currentImagePath != 'resources/productsImages/basic.jpg')//delete the current image form the storage
							unlink('./' . $currentImagePath);

						//make up the new name for the image and move it to the storage
						$image_path = 'resources/productsImages/' . str_replace(' ', '_', $data['id']) 
										. $request->file('image')->getClientOriginalName();

						$request->file('image')->move('resources/productsImages/', str_replace(' ', '_', $data['id']) . 
												$request->file('image')->getClientOriginalName() );
					}
					else{
						$image_path = $currentImagePath;
					}

					$updateObject = array(
						'id' => clean_input( $data['id'] ),
						'product_name' => clean_input($data['name']),
						'manufacturer' => clean_input($data['manufacturer']),
						'price' => round( clean_input($data['price']), 2 ),
						'price' => round( clean_input($data['price']), 2 ),
						'discounted_price'=> round ($data['discount'] != 0 ? $data['price'] * (100 - $data['discount'])/100 : 0 , 2),
						'discount' => isset($data['discount']) ? $data['discount'] : 0,
						'quantity' => $data['quantity'],
						'category' => $data['category'],
						'sub_category' => $data['subCategory'],
						'image_path' =>  $image_path,
						'updated_at' => time(),
						'updated_by' => Auth::guard('admin')->user()->id
					);

					//update the data
					DB::table('products')->where('id', $data['id'])->update( $updateObject );

					//write in logs
					$log = new Log;
					$logText = 'updated the product with the id ' . $data['id'];
					$log->insert($logText, 'products', Auth::guard('admin')->user()->id );

					return json_encode($updateObject);
				}
				else
					return json_encode('unauthorized');
			}
		}

		//function that removes a product from the database
		public function removeProduct(Request $request){
			$data = $request->all();
			$position = Auth::guard('admin')->user()->position;

			if(empty(DB::table('products')->where('id', $data['id'])->value('id')) )//if there is no product with the given id
				return json_encode(false);

			if($position == '0'){

				//get the product's image
				$currentImagePath = DB::table('products')->where('id', $data['id'])->value('image_path');
				
				if($currentImagePath != 'resources/productsImages/basic.jpg')//delete the current image form the storage
						unlink('./' . $currentImagePath);

				//write in logs
				$log = new Log;
				$logText = 'deleted the product ' . DB::table('products')->where('id', $data['id'])->value('product_name')
							. '( ' . $data['id'] . ' )';
				$log->insert($logText, 'products', Auth::guard('admin')->user()->id);

				DB::table('products')->where('auto_id', $data['auto_id'])->delete();

				return json_encode(true);
			}
			else{
				//write the log
				$log = new Log;
				$logText = 'tried to remove the product with the id ' . $data['id'];
				$log->insert($logText, 'products', Auth::guard('admin')->user()->id);

				return json_encode('unauthorized');
			}
				
			
		}

		//get all the logs from a specific category
		public function getLogs(Request $request, $category){
			$log = new Log;
			$logs = $log->where('category', $category)->orderBy('created_at', 'desc')->get();

			for($i = 0; $i < count($logs); $i++){
				//compose the full name from an employee's id
				$fullName = DB::table('employees')->where('id', $logs[$i]->employee)->value('first_name')
							. ' ' . DB::table('employees')->where('id', $logs[$i]->employee)->value('last_name');

				$logs[$i]->employee = $fullName;
			}
			
			return json_encode($logs);
		}
	}
?>