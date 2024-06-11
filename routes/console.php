<?php

use App\Service\BnmService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $service = new BnmService();
    $service->insertExchangeRates();

    Log::info('Exchange rates inserted successfully.');
})->dailyAt('16:30');
