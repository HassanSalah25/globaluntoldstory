<?php

namespace App\Http\Requests;

use App\Support\AdminLocales;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'service' => ['nullable', 'string', 'max:255'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'message' => ['nullable', 'string', 'max:5000'],
            'budget' => ['nullable', 'string', 'max:100'],
            'locale' => ['nullable', 'string', AdminLocales::validationRule()],
        ];
    }
}
