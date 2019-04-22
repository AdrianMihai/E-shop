<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

class ProductsController extends Controller
{
    //
   
   	//function that calculates the average of reivews grades
	public function submitReview(Request $request){
        /*$data = $request->all();

        DB::table('reviews')->insert([
            ''
            ])*/

	}

   	//get data and reviews for a product
	public function getProductData(Request $request, $productId = null){
		$productData['data'] = DB::table('products')->where('id', $productId)->get();

    	$productData['reviews'] = DB::table('reviews')->where('product_id', $productId)->get();

    	$productData['review_average'] = DB::select('SELECT Avg(grade) AS averageGrade FROM reviews WHERE product_id = ? ', [$productId]);

        for($i = 0; $i < count($productData['reviews']); $i++){
            $productData['reviews'][$i]->username  =
                                        DB::table('users')->where('id', $productData['reviews'][$i]->user_id )->value('username');
        }
    	return json_encode($productData);
	}

    public function getProductView(Request $request, $productId = null){
    	
    	return view('productView')->with('productId', $productId);

    }
}
