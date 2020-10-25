
## About Project

A program that can price a cart of products, accept multiple products, combine offers, and display a total detailed bill in different currencies (based on user selection).

Solution:
- Assume the cart request is entering the program as an API request
- Handle the API request to get the products quantity from the JSON string.
- Do the problem solving job to combine the offers.
- Convert the currency using fixer.io API free account to get the rates.
- Arranging the API response as requested.

Architecture:
- Architectural Model-View-Controller Pattern using Laravel 5.8 gave a better implementation for the Restful API and gave an opportunity to use Frontend templates supported by Laravel (Blade) if needed in the future.

## How to run the project

- Open the project code and update .env file as follows:
```
DB_DATABASE=edfa3ly
DB_USERNAME=YOUR USERNAME
DB_PASSWORD=YOUR PASSWORD
```
- Import [edfa3ly.sql] file from inside the project.
- open your terminal in the directory where you have the project.
- type in terminal 
```bash
cd edfa3ly
php artisan serve
```
- open postman and run this route with GET request:
	"localhost:8000/"
	Now you can check all available products.
- open postman and run this route with POST request:
	"localhost:8000/"
    - insert in the body:
``` php
{
"cart" : "T-shirt T-shirt shoes",
"currency" : "EGP"
}
```
- Optional to add the currency.
- feel free to change the products in the cart.

## How to test your project

- open your terminal in the directory where you have the project.
- type in terminal 
```bash
cd edfa3ly
vendor/bin/phpunit
```
- 8 tests, 28 assertions should be passed smoothly.

## Files Functionality

- Controllers/ProductController : used to calculate the cart bill and to fetch all available products. 
- Controllers/ConvertController : used to Access currencies rate using fixer.io API free account and to Convert currencies to requested currency.
- Traits/DataTrait : simple trait for data fetching Ex. fetching product price using it's name.
- Product : Product Model to interact with the database.
- Routes/api : used to handle the routes.

## Root Folder

The root folder has
- The application files
- my Mysql database , it's name : edfa3ly.sql

## Comments

- It could be a better implementation if we used a paid account for accessing currency rate and also we can get currencies symbols.
