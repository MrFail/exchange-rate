<?php

namespace App\Service;

use App\Models\ExchangeRate;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExchangeRateService
{
    /**
     * @throws Exception
     */
    public static function filter($filters): ?LengthAwarePaginator
    {
        return ExchangeRate::query()
            ->with(['currency', 'bank'])
            ->when(! empty($filters['date']), fn ($query) => $query->where('exchange_rate_date', Carbon::parse($filters['date'])->format('Y-m-d')))
            ->when(! empty($filters['bank']), fn ($query) => $query->where('bank_id', $filters['bank']))
            ->when(! empty($filters['currency']), fn ($query) => $query->where('currency_id', $filters['currency']))
            ->paginate();
    }
}
