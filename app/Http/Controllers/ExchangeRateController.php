<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExchangeRate\ListRequest;
use App\Models\Bank;
use App\Models\Currency;
use App\Service\ExchangeRateService;
use Illuminate\Contracts\View\View;

class ExchangeRateController extends Controller
{
    public function index(ListRequest $request): View
    {
        $filters = $request->validated();

        $exchange_rates = ExchangeRateService::filter($filters);

        $banks = Bank::whereEnabled(true)->get();

        $currencies = Currency::whereEnabled(true)->get();

        return view('exchange-rate.list', compact('exchange_rates', 'banks', 'currencies', 'filters'));
    }
}
