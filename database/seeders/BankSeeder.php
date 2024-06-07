<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = json_decode(file_get_contents(__DIR__.'/json/banks.json'), JSON_OBJECT_AS_ARRAY);

        Bank::insert($currencies);
    }
}
