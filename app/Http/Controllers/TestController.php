<?php

namespace App\Http\Controllers;

use App\Service\BnmService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __invoke(Request $request): void
    {
        $service = new BnmService();

        $service->insertExchangeRates();
    }
}
