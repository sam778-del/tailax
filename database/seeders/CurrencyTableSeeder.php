<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create([
            "currecny_name" => "USD",
            "currency_symbol" => "$",
            "is_default" => true,
            "amount" => 1.00
        ]);
    }
}
