<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = json_decode(file_get_contents(__DIR__.'/json/currencies.json'), JSON_OBJECT_AS_ARRAY);

        Currency::insert($currencies);
    }
}
