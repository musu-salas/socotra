<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Currency;

class CurrencyTableSeeder extends Seeder {
    
    public function run() {

        $currencies = [
            ['code' => 'USD', 'symbol' => '$'],
            ['code' => 'EUR', 'symbol' => '€'],
            ['code' => 'AUD', 'symbol' => '$'],
            ['code' => 'GBP', 'symbol' => '£'],
            ['code' => 'CAD', 'symbol' => '$'],
            ['code' => 'DKK', 'symbol' => 'kr. '],
            ['code' => 'CHF', 'symbol' => 'Fr. '],
            ['code' => 'HKD', 'symbol' => '$'],
            ['code' => 'JPY', 'symbol' => '¥'],
            ['code' => 'NOK', 'symbol' => 'kr '],
            ['code' => 'NZD', 'symbol' => '$'],
            ['code' => 'SEK', 'symbol' => 'kr '],
            ['code' => 'ZAR', 'symbol' => 'R ']
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate([
                'code' => $currency['code']
            ], $currency);
        }
    }

}