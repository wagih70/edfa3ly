<?php

namespace Tests\Feature;

use App\Http\Controllers\ConvertController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Edfa3lyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_get_all_products()
    {
        $response = $this->json('GET', '/');

        $response
            ->assertStatus(200)
            ->assertJson([
                ['name' => 't-shirt'],
                ['name' => 'pants']
            ]);
    }

    public function test_calculate_without_discount()
    {
        $response = $this->json('POST', '/', ['cart' => 'T-shirt pants jacket']);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                    'subtotal',
                    'taxes',
                    'total'
                ]);
    }

    public function test_calculate_with_only_shoes_discount()
    {
        $response = $this->json('POST', '/', ['cart' => 'T-shirt shoes jacket']);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                    'subtotal',
                    'taxes',
                    'discounts' => [
                        '10% off shoes'
                    ],
                    'total'
                ]);
    }

    public function test_calculate_with_only_jacket_discount()
    {
        $response = $this->json('POST', '/', ['cart' => 'T-shirt T-shirt cairo_pattern_set_filter(pattern, filter) jacket']);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                    'subtotal',
                    'taxes',
                    'discounts' => [
                        '50% off jackets'
                    ],
                    'total'
                ]);
    }

    public function test_calculate_with_shoes_and_jacket_discount()
    {
        $response = $this->json('POST', '/', ['cart' => 'T-shirt T-shirt shoes jacket pants']);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                    'subtotal',
                    'taxes',
                    'discounts' => [
                        '10% off shoes',
                        '50% off jackets'
                    ],
                    'total'
                ]);
    }

    public function test_get_conversion_rate()
    {
        // Set currency to USD because API base currency is EUR
        $currency = 'EUR';
        $conversion = new ConvertController;
        $rate = $conversion->getConversionRate($currency);
        $this->assertEquals(1,$rate);
    }

    public function test_convert_method()
    {
        // Set currency to USD because database default currency is USD
        $currency = 'USD';
        $conversion = new ConvertController;
        $convert = $conversion->convert($currency);
        $this->assertEquals(1,$convert);
    }
}
