<?php
namespace App\Http\Traits;

use App\Product;

trait DataTrait {

    public function retrievePrice($product, $rate)
	{
		// fetch the Product price using its name
		$product_price = Product::where('name', $product)->value('price');
    	$product_price = $product_price * $rate;
    	return $product_price;
	}
}