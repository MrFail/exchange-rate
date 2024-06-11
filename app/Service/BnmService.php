<?php

namespace App\Service;

use App\Enums\Bank as BankEnum;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BnmService
{
    protected string $exchange_rate_url;

    protected string $exchange_rate_date;

    protected Bank $exchange_rate_source;

    /**
     * @throws Exception
     */
    public function __construct($date = null)
    {
        $bank = Bank::where('code', BankEnum::BNM)->first();

        if (! $bank) {
            throw new Exception('Bank not found.');
        }

        $exchange_rate_url = config('services.bnm.exchange_rate_url');

        if (! $exchange_rate_url) {
            throw new Exception('Exchange rate url is missing.');
        }
        $this->exchange_rate_source = $bank;

        $this->exchange_rate_url = $exchange_rate_url;

        $this->exchange_rate_date = ! empty($date)
            ? Carbon::parse($date)->format('d.m.Y')
            : Carbon::now()->format('d.m.Y');
    }

    /**
     * @throws Exception
     */
    private static function parseXml(string $string): array
    {
        $xml = @simplexml_load_string($string);

        if (! $xml) {
            throw new Exception('XML parse error.');
        }
        $result = json_decode(json_encode((array) $xml), true);

        return Arr::map($result['Valute'], function ($item, $key) {
            return [
                'code' => $item['CharCode'],
                'value' => $item['Value'],
            ];
        });
    }

    public function getExchangeRateUrl(): string
    {
        return $this->exchange_rate_url;
    }

    public function getExchangeRateDate(): string
    {
        return $this->exchange_rate_date;
    }

    public function insertExchangeRates()
    {
        try {
            $exchangeRates = $this->getExchangeRatesFromBank();

            $this->storeToDatabase($exchangeRates);
        } catch (Exception $e) {
            Log::error('Exchange Rate Error: ', [$e->getMessage()]);
        }
    }

    /**
     * @throws Exception
     */
    protected function getExchangeRatesFromBank(): array
    {
        $response = Http::withOptions(['verify' => false])
            ->get($this->getExchangeRateUrl(), [
                'date' => $this->getExchangeRateDate(),
            ]);

        if ($response->failed()) {
            throw new Exception('Connection error.');
        }

        $body = $response->body();

        if (empty($body)) {
            throw new Exception('Empty exchange rate response.');
        }

        $exchangeRate = self::parseXml($body);

        if (empty($exchangeRate)) {
            throw new Exception('Empty exchange rate response.');
        }

        return $exchangeRate;
    }

    /**
     * @throws Exception
     */
    protected function storeToDatabase($exchangeRateList): void
    {
        $currencies = Currency::all();
        $insertArray = [];
        if ($currencies->isNotEmpty()) {
            $currencies = $currencies->keyBy('code');
            $currenciesKeys = $currencies->keys();

            foreach ($exchangeRateList as $exchangeRate) {
                if ($currenciesKeys->contains($exchangeRate['code'])) {
                    $insertArray[] = [
                        'exchange_rate_date' => Carbon::parse($this->getExchangeRateDate())->format('Y-m-d'),
                        'value' => $exchangeRate['value'],
                        'currency_id' => $currencies[$exchangeRate['code']]->id,
                        'bank_id' => $this->exchange_rate_source->id,
                    ];
                }
            }
        }

        if (! empty($insertArray)) {
            ExchangeRate::query()->insert($insertArray);
        }
    }
}
