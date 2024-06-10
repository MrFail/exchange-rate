<?php

namespace App\Http\Requests\ExchangeRate;

use App\Models\Bank;
use App\Models\Currency;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'sometimes',
                'date',
                'date_format:Y-m-d',
            ],
            'currency' => [
                'sometimes',
                Rule::exists(app(Currency::class)->getTable(), 'id'),
            ],
            'bank' => [
                'sometimes',
                Rule::exists(app(Bank::class)->getTable(), 'id'),
            ],
        ];
    }
}
