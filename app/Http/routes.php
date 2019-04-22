<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	
    return view('start');
});

Route::get('reactStart', function(){
	return view('react-start');
});

Route::get('previewDiscountedProducts', 'User@preview_products');

Route::get('latestProducts', 'User@latest_products');

Route::get('cartContents', 'User@cart_contents');

Route::get('shipping_prices', 'User@shippingPrices');


Route::post('addToCart', 'User@addItemToCart');

Route::post('removeFromCart', 'User@removeItemFromCart');

Route::post('updateCart', 'User@updateQuantityFromCart');

Route::get('product/{productId?}', 'ProductsController@getProductView');

Route::get('product/{productId?}/getData', 'ProductsController@getProductData');

//routes for orders
Route::get('cart/info', 'User@orderInfo');

Route::get('cart/{step?}', 'User@orderSteps');

Route::post('step-2/check', 'User@orderStep2Form');

Route::post('step-3/check', 'User@orderStep3Form');

Route::post('paypal/checkout', 'User@paypalCheckout');

Route::group(['middleware' => ['guest'] ], function(){

	Route::get('login', function(){

		return view('login');
	});

	//Sign in the user
	Route::post('login', 'User@login_handler');	
	 
	//Check if the email already exists
	Route::post('login/email_check', 'User@signupFirstStep');

	//Insert user data
	Route::post('login/submit_data', 'User@submit_data');

	
});

//route for submitting a review
Route::post('review/submit', 'ProductsController@submitReview');

//Log in with facebook
Route::get('login/facebook', 'User@facebookRedirect');

Route::get('login/facebookHandler', 'User@facebookHandler');

Route::get('locations', 'User@get_locations');

Route::group(['middleware' => ['auth']], function(){

	Route::get('logout', 'User@logout');

	Route::get('settings', function(){
		return view('user-settings');
	});

	Route::get('get_settings', 'User@user_settings');

	Route::post('update_settings', 'User@update_settings');
});



//Admin related routes
Route::get('admin', ['middleware' => 'admin_ok', 'before' => 'csrf', function(){

	return view("admin_layouts.main")->with("admin_pass", file_get_contents('../public/adminpass.txt') );
		
}]);
	
Route::any('admin/login', 'AdminsController@login');

Route::group(['middleware' => ['admin'] ], function(){

	
	Route::get('admin/dashboard', function(){
		return view('admin_layouts.dashboard');
	});

	Route::post('admin/image_check', 'AdminsController@image_process');

	/* EMPLOYEES */
	Route::get('admin/employees', function(){
		return view('admin_layouts.employees');
	});

	Route::get('admin/positions', 'EmployeesController@getPositions');

	Route::get('admin/get_employees', 'EmployeesController@getAll');
	
	Route::post('admin/add_employee', 'EmployeesController@store');

	/* LOGS */
	Route::get('admin/logs/{category?}', function($category = ''){
		if($category == 'products' || $category == 'employees' || $category == 'orders'){
			return view('admin_layouts.logs');
		}
		else
			return redirect('admin/logs/employees');
	});
	Route::get('admin/logs/{category?}/get_them', 'AdminsController@getLogs' );

	/* PRODUCTS */
	Route::get('admin/products', 'AdminsController@searchProducts');

	Route::get('admin/get_products', 'AdminsController@load_products');
		
	Route::get('admin/get_categories', 'AdminsController@get_categories');
	
	Route::post('admin/add_product', 'AdminsController@add_product');//Add new product
	
	Route::post('admin/update_product', 'AdminsController@updateProduct');//Update data for a product

	Route::post('admin/remove_product', 'AdminsController@removeProduct');//Remove a product from the database

	/* SETTINGS */
	Route::get('admin/settings','AdminsController@getShippingSettings');

	Route::post('admin/change_shipping_prices', 'AdminsController@saveShippingSettings');

	/* LOGOUT */
	Route::get('admin/logout', 'AdminsController@logout');
});
	