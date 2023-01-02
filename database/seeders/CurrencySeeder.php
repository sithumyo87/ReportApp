<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currency = new Currency();
        $currency->Currency_name = 'USD';
        $currency->UnitPrice = 0;
        $currency->symbol = '$';
        $currency->save();

        $currency = new Currency();
        $currency->Currency_name = 'MMK';
        $currency->UnitPrice = 0;
        $currency->symbol = '/-';
        $currency->save();
    }
}
