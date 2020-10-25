<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConvertController extends Controller
{
	// Access currencies rate using fixer.io API free account with EUR base currency
    public function getConversionRate($currency)
	{
		// set API Endpoint and API key 
		$endpoint = 'latest';
		$access_key = '1ee57985fb936f42d5e34e242d29c32d';

		// Initialize CURL:
		$ch = curl_init('http://data.fixer.io/api/'.$endpoint.'?access_key='.$access_key.'');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Store the data:
		$json = curl_exec($ch);
		curl_close($ch);

		// Decode JSON response:
		$exchangeRates = json_decode($json, true);

		// Access the exchange rate values:
		return $exchangeRates['rates'][$currency];
	}

	// Convert currencies to requested currency using fixer.io API free account with EUR base currency
	public function convert($request_currency)
	{
		// Conversion Rate from EURO to USD
		$euro_to_usd = $this->getConversionRate('USD');

		// Conversion Rate from Euro to Requested currency
		$euro_to_request = $this->getConversionRate(strtoupper($request_currency));

		// Conversion Rate from USD to Requested currency
		$usd_to_request =  $euro_to_request/$euro_to_usd;
		return $usd_to_request;
	}
}
