<?php

namespace App\Http\Controllers\AuthController;

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

use DB;

use Hash;

use Socialite;

class User extends Controller
{
    //

    // remove unecessary spaces, backslashes, convert HTML characters
    private function clean_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    private function var_dump_ret($mixed = null) {
        ob_start();
        var_dump($mixed);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    //checks if there is already a user with the same name
    public function isUsername(Request $request, $username){
        if(DB::table('users')->where('username', $username)->value('username'))
            return true;
        return false;
    }

    //checks if there is already a user with the same email
    public function isEmail(Request $request, $email){
        if(DB::table('users')->where('email', $email)->value('email'))
            return true;
        return false;
    }

	public function signupFirstStep(Request $request){
		$data = $request->all();

		if(!$data['email'] || !$data['username'])
			return json_encode(false);

		if($this->isUsername($request, $data['username']) )
			return 'username';
		if($this->isEmail($request, $data['email']) )
			return 'email';

		return json_encode(true);
	}

    //Submit all the data for creating a new account
	public function submit_data(Request $request){

		if($request->isMethod('post')){
			$user_data = $request->all();
			$user_data['password'] = Hash::make($user_data['password']);

            if(!$user_data['username'] || !$user_data['email'] )
                return json_encode(false);
            else
                if(!$this->isUsername($request, $user_data['username']) && !$this->isEmail($request, $user_data['email'])) {
                    Db::table('users')->insert($user_data);
                    return json_encode(true);
                }
                else
                    return json_encode(false);
		}
	}

    public function login_handler(Request $request){

    	if($request->isMethod('post')){
    		$user_data = ['email' => $request->input('email'), 'password' => $request->input('password')];

    		$request->flashOnly(['email']);

    		if( empty(DB::table('users')->where('email', $user_data['email'])->value('email')) ){

    			return view('login')->with('error_message', 'There is no account registered with this mail.');
    		}	

    		//Check the password
    		$password = DB::table('users')->where('email', $user_data['email'])->value('password');

    		if( !Hash::check($user_data['password'], $password)){
    			return view('login')->with('error_message', 'The password you entered is wrong.');
    		}
    		else{
                $remember = null !== $request->input('remember') ? true : false;
                if(Auth::attempt($user_data, $remember))
                    return redirect('/'); 
                else
                    return view('login')->with('error_message', 'Sign in not working!');
    		}

    	}
    }

    public function facebookRedirect(){
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookHandler(){
        $user = Socialite::driver('facebook')->user();

        //return var_dump_ret($user);
    }
    public function logout(){
    	Auth::logout();

        //$session->put('orderInfo', json_encode([])); //clear completed information in order formular

    	return redirect('/');
    }

    //function that returns all the personal data of a user
    public function user_settings(){
    	$data = [
    				'first_name' => Auth::user()->first_name,
    				'last_name' => Auth::user()->last_name,
    				'phone_number' => Auth::user()->phone_number,
                    'county' => (string)Auth::user()->county,
                    'city' => (string)Auth::user()->city,
    				'adress' => Auth::user()->shipping_adress
    			];
        return json_encode($data);
    	
    }
    public function get_locations(Request $request){
        $counties = DB::select('SELECT id, name FROM account_county');
        for($i = 0; $i < count($counties); $i++){
            $id = $counties[$i]->id;
            $counties[$i]->cities = json_encode(DB::select('SELECT id,name FROM account_city WHERE county_id = ? ORDER BY name ASC'
                                                , [$id] ));
        }

        return json_encode($counties);
    }
    public function update_settings(Request $request){
        if($request->isMethod('post')){
            $settings = $request->all();
            /*foreach ($settings as $key => $value) {
                # code...
            }*/
            DB::table('users')->where('id', Auth::user()->id)->update($settings);

            return json_encode(true);
        }   
    }

    public function reviewData($productId){
        $reviewData = [];
        $reviewData['average'] = DB::select('SELECT Avg(grade) AS averageGrade FROM reviews WHERE product_id = ? ', [$productId]);
        $reviewData['numberOfReviews'] =  count(DB::select('SELECT * FROM reviews WHERE product_id = ? ', [$productId]));

        return $reviewData;
    }

    public function preview_products(){
        $products = DB::table('products')->where('discounted_price', '<>', 0)
                                            ->orderBy('addedon', 'desc')
                                            ->take(3)->get();
        for($i = 0; $i < count($products); $i++)
            $reviewsData[] = $this->reviewData($products[$i]->id);

        return json_encode([$products, $reviewsData]);    
    }

    public function latest_products(){
        $undiscounted_products = DB::table('products')->where('discounted_price', '=', 0)
                                                        ->orderBy('updated_at', 'desc')
                                                        ->take(9)->get();
        $discounted_products =  DB::table('products')->where('discounted_price', '<>', 0)
                                            ->orderBy('updated_at', 'desc')
                                            ->take(9)->get();

        for($i = 0; $i < count($undiscounted_products); $i++) {
            $undiscounted_products[$i]->reviewsData = $this->reviewData($undiscounted_products[$i]->id);
        }  

        for($i = 0; $i < count($discounted_products); $i++) {
            $discounted_products[$i]->reviewsData = $this->reviewData($discounted_products[$i]->id);
        }                          
        return json_encode([$undiscounted_products, $discounted_products]);
    }

    //Get all the product in the cart
    public function cart_contents(Request $request){
        //
        
        if($request->session()->has('products')){
            return json_encode($request->session()->get('products'));
        }
        else{
            $request->session()->put('products', []);
            return json_encode($request->session()->get('products'));
        }
    }

    //get the shipping price and the price from which the shipping becomes free
    public function shippingPrices(){
        $shippingData = DB::table('settings')->where('related_to', 'shipping')->value('information');
        
        return $shippingData;
    }

    //Add new item to the shopping cart
    public function addItemToCart(Request $request){
        if($request->isMethod('post')){
            
            $products = $request->session()->get('products');
            for($i = 0; $i < count($products); $i++){
                $_this = $products[$i];
                if($_this['id'] == $request->id)
                    return json_encode(false);
            }
            
            $request->session()->push('products', $request->all());
            return $request->all(); 
        }
        else
            return redirect('/');
    }

    //Remove an item from the cart
    public function removeItemFromCart(Request $request){
        if($request->isMethod('post')){
            $products = $request->session()->get('products');
            $num_products = count($products);
            if($num_products > 0){
                for($i = 0; $i < $num_products; $i++){
                    $_this = $products[$i];
                    if($_this['id'] == $request->id){
                        array_splice($products, $i, 1);
                        $request->session()->put('products', $products);
                        return json_encode(true);
                    }
                }
                return json_encode(false);
            }   
            else
                return json_encode(false);
        }
        else
            return redirect('/');
    }

    //Update the quantity for a specific product in the shopping cart
    public function updateQuantityFromCart(Request $request){
        if($request->isMethod('post')){
            if($request->session()->has('products')){
                $products = $request->session()->get('products');
                for($i = 0; $i < count($products); $i++){
                    if($products[$i]['id'] == $request->id){
                        if($request->quantity > $products[$i]['quantity']){
                            $products[$i]['picked_quantity'] = $products[$i]['quantity'];
                        }
                        else if($request->quantity >= 0){
                            $products[$i]['picked_quantity'] = $request->quantity; 
                        }
                        else{
                            $products[$i]['picked_quantity'] = 1;
                        }
                        
                        $request->session()->put('products', $products);
                        return json_encode($products[$i]['picked_quantity']);
                    }
                }
                return json_encode(true);
            }
            return json_encode(false);
        }
        else
            return redirect('/');
    }

    //function that checks if the shopping cart is empty
    public function orderStep1_check(Request $request){
        $session = $request->session();
        if($session->has('products'))
            if(count($session->get('products')) > 0)
                return true;
        return false;
    }
    
    //functions that check if all the data has been completed for making an order
    public function orderStep2Check(Request $request){
        $session = $request->session();
        //$string = '';

        if($session->has('orderInfo')){
            $info = json_decode( $session->get('orderInfo') );
            foreach ($info as $data) {
                foreach ($data as $key => $value) {
                    if(!isset($value) || !$value)
                        return false;
                }
            }
            return true;
        }
        return false;
        
    }
    public function orderStep2Form(Request $request){
        $orderData = $request->all();
        $session = $request->session();

        $info = [];

        $info["personalInfo"] = [
                "firstName" => $request['firstName'] ,
                "lastName" => $request['lastName'],
                "email" => $request['email'],
                "phoneNumber" => $request['phoneNumber']
        ];

        $info["personalAdress"] = [
                "county" => $request['personal_county'],
                "city" => $request['personal_city'],
                "adress" => $request['personal_adress']
        ];
        $info["shippingAdress"] = [
                "county" => $request['shipping_county'],
                "city" => $request['shipping_city'],
                "adress" => $request['shipping_adress']
        ];

        $session->put('orderInfo', json_encode($info)); //put the info in session

        if(! $this->orderStep2Check($request))
            return redirect('/cart/step-2');
        else
            return redirect('/cart/step-3');
    }

    public function orderStep3Check(Request $request){
        $session = $request->session();
        if($session->has('paymentMethod'))
            return true;
        return false;
    }

    public function orderStep3Form(Request $request){
        $data = $request->all();
        $session = $request->session();
        $payment = $session->get('paymentMethod');


        if(!$request->has('payment')){
            return redirect('cart/step-3');
        }

        $session->put('paymentMethod', $data['payment']);

        //return json_encode($this->orderStep3Check($request));
        if($this->orderStep3Check($request))
            return redirect('cart/step-4');
        else
            return redirect('cart/step-3');
    }

    public function orderInfo(Request $request, $direct_call = false){
        $session = $request->session();

        //if the user is signed in and he has not completed any data for the order yet

        if(!$session->has('orderInfo')){
            
            if(Auth::check()){
                $info = [];
                $userSettings = json_decode( $this->user_settings() );
                $info["personalInfo"] = [
                                            "firstName" => $userSettings->first_name ,
                                            "lastName" => $userSettings->last_name,
                                            "email" => Auth::user()->email,
                                            "phoneNumber" => $userSettings->phone_number
                                        ];
                $info["personalAdress"] = [
                                            "county" => $userSettings->county,
                                            "city" => $userSettings->city,
                                            "adress" => $userSettings->adress
                                        ];
                $info["shippingAdress"] = [];

                $session->put('orderInfo', json_encode($info)); //put the info in session
            }

            
        }
        return $session->get('orderInfo'); 
    }

    public function currencyConvert($amount, $from, $to){
        //$url = "https://www.google.com/finance/converter?a=" . $amount . '&from=' . $from . '&to=' . $to ;
        $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
        $data = file_get_contents($url);
        preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
        $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
        return round($converted, 2); 
    }

    //function that calculates the total cost of the products in the shopping cart
    public function totalCart(Request $request){
        $productInCart =json_decode($this->cart_contents($request));
        $shippingCosts = json_decode($this->shippingPrices());

        $sum = 0;
        
        for($i = 0; $i < count($productInCart); $i++){
            $_this = $productInCart[$i];
            $sum += round($_this->final_price * $_this->picked_quantity, 2);
        }

        $sum += ($sum >= $shippingCosts->free_from) ? 0 : $shippingCosts->price;

        return ($sum >= $shippingCosts->free_from) ? 0 : $shippingCosts->price;
    }

    public function paypalCheckout(Request $request){
        if($request->isMethod('post')){
            
        }
        else{
            return json_encode(false);
        }
    }

    //function that empties the shopping cart
    public function empty_order_info(Request $request){
        $session = $request->session();

        $session->forget('products');
        $session->forget('orderInfo');
    }

    public function checkout(Request $request){
        $session = $request->session();

        $userId = Auth::user()->id;

        $products = $session->get('products');

        $orderInfo = json_decode( $session->get('orderInfo') );

        $shippingCost = $this->totalCart($request);

        $orderId = DB::table('orders')->insertGetId([
            'orderBy' => $userId,
            'shipping_county' => $orderInfo->shippingAdress->county,
            'shipping_city' => $orderInfo->shippingAdress->city,
            'shipping_adress' => $orderInfo->shippingAdress->adress,
            'shipping_price' => $shippingCost,
            'created_at' => time()
            ]);

        $productsToBeInserted = [];

        for($i = 0; $i < count($products); $i++){
            $productsToBeInserted[] = [
                'product_id' => $products[$i]['id'],
                'order_id' => $orderId,
                'price_per_piece' => $products[$i]['final_price'],
                'quantity' => $products[$i]['picked_quantity']
            ];
        }
        
        DB::table('ordered_products')->insert($productsToBeInserted);

        $this->empty_order_info($request);
    }

    //function that manages the steps the user needs to be taken through when making an order
    public function orderSteps(Request $request, $step = null ){
        
        switch ($step) {
            case 'step-1':
                return view('checkout')->with('step', $step);
                break;
            case 'step-2' :
                if(!Auth::check())
                        return redirect('login');

                if($this->orderStep1_check($request)){
                    //$this->orderInfo($request, true);
                    return view('checkout')->with(['step' => $step, 'info' => 'ceva']);
                }
                else{
                    return redirect('cart/step-1');  
                }
                    
                break;
            case 'step-3':
                if($this->orderStep2Check($request))
                    return view('checkout')->with(['step' => $step]);
                else
                    return redirect('cart/step-2');
                break;
            case 'step-4';
                if( $this->orderStep1_check($request) && $this->orderStep2Check($request) && $this->orderStep3Check($request)){
                    $this->checkout($request);

                    return view('checkout')->with(['step' => $step]);
                }
                else
                    return redirect('cart/step-3');
                break;
            default:
                return redirect('cart/step-1');
                break;
        }
        
    }
}
