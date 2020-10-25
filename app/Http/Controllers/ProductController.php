<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;

class ProductController extends ConvertController
{
	use DataTrait;

	public function getAllProducts()
	{
		// Show all available products
		$products = Product::all()->toJson(JSON_PRETTY_PRINT);
		return response($products,200);
	}

    public function calculate(Request $request)
    {
    	//Checking if the request has the right input
    	if(!$request->has('cart')){
    		return response()->json([
        		'message' => 'Please insert your products using cart key'
        	], 422);
    	}

    	// Convert data to the requested currency
    	if($request->has('currency')){   		
    		$symbol = strtoupper($request->currency);
    		$usd_to_request =  $this->convert($request->currency);   		
    	} else {
    		$symbol = 'USD';
    		$usd_to_request = 1;
    	}

    	$result = [];
    	$discounts = [];
    	$subtotal = 0;
    	$total = 0;

    	// Convert the input string to an array
    	$cart = explode(' ',strtolower($request->cart));
    	$quantity = array_count_values($cart);

    	// Calculate T-shirts total price if selected
    	if(array_key_exists('t-shirt', $quantity)){

    		$t_shirt_price = $this->retrievePrice('t-shirt', $usd_to_request);
    		$total_t_shirt_price = $quantity['t-shirt'] * $t_shirt_price;
    		$subtotal += $total_t_shirt_price;
    		$total += $total_t_shirt_price;
    	}

    	// Calculate Shoes total price if selected
    	if(array_key_exists('shoes', $quantity)){

    		$shoes_price = $this->retrievePrice('shoes', $usd_to_request);
	    	$discounts['10% off shoes'] = round($quantity['shoes'] * 0.1 * $shoes_price,2);
	    	$total_shoes_price = $quantity['shoes'] * 0.9 * $shoes_price;
	    	$subtotal_shoes_price = $quantity['shoes'] * $shoes_price;
	    	$subtotal += $subtotal_shoes_price;
	    	$total += $total_shoes_price;
	    }

	    // Calculate Jackets total price if selected
    	if(array_key_exists('jacket', $quantity)){

    		$jacket_price = $this->retrievePrice('jacket', $usd_to_request);

	    	if($quantity['t-shirt'] >=2){
	    		$jacket_quantity_on_sale = (floor($quantity['t-shirt']/2));
	    		$actual_jacket_quantity = $quantity['jacket'];

	    		if($actual_jacket_quantity > $jacket_quantity_on_sale){

	    			$jacket_quantity_not_on_sale = $actual_jacket_quantity - $jacket_quantity_on_sale;

	    			$jacket_price_on_sale = $jacket_quantity_on_sale * ($jacket_price/2); 

	    			$jacket_price_not_on_sale = $jacket_quantity_not_on_sale * $jacket_price;

	    			$total_jacket_price =  $jacket_price_on_sale + $jacket_price_not_on_sale;

	    			$discounts['50% off jackets'] = round($jacket_price_on_sale,2);

	    		}else {

	    			$total_jacket_price = $actual_jacket_quantity * ($jacket_price/2);

	    			$discounts['50% off jackets'] = round($total_jacket_price,2);
	    		}

	    	}else{
                
	    		$total_jacket_price = $quantity['jacket'] * $jacket_price;
	    	}

	    	$subtotal += $quantity['jacket'] * $jacket_price;
	    	$total += $total_jacket_price;
	    }

	    // Calculate Pants total price if selected
	    if(array_key_exists('pants', $quantity)){

	    	$pants_price = $this->retrievePrice('pants', $usd_to_request);

    		$total_pants_price = $quantity['pants'] * $pants_price;
    		$subtotal += $total_pants_price;
    		$total += $total_pants_price;
    	}

    	// Calculating taxes
    	$taxes = $subtotal * 0.14;
    	$total = $total + $taxes;


    	$result['subtotal'] = round($subtotal,2);
    	$result['taxes'] = round($taxes,2);

    	// Adding discounts subValue ONLY when there is one
    	if(array_key_exists('shoes', $quantity)){

    		$result['discounts'] = $discounts;

    	}

    	if($quantity['t-shirt'] >=2 && array_key_exists('jacket', $quantity)){

    		$result['discounts'] = $discounts;

    	}

    	$result['total'] = round($total,2);

    	// Arranging the main Json Value with the discount subValue
    	foreach ($result as &$value) {
    		if(is_numeric($value)){
    			$value = $value.' '.$symbol;
    		}else {
    			foreach ($value as &$subValue) {
    				$subValue = $subValue.' '.$symbol;
    			}
    		}    
    	}
    	unset($value);

    	return response($result,200);

	}

}
