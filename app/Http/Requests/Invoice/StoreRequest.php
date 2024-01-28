<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'table-id' => [
                'required',
                // Rule::in(['T1.1','T1.2','T1.3','T1.4','T1.5','T1.6','T1.7','T1.8','T1.9','T1.10'])
            ],
            'name' => [
                'required',
            ],
            'price' => [
                'required',
            ],
            'quantity' => [
                'required',
            ],
            'total-price' => [
                'required',
            ]
        ];
    }
}
