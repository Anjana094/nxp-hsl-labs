<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // No auth for now
    }

    public function rules()
    {
        return [
            'provider_id' => 'required|exists:providers,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'provider_id.required' => 'Provider is required.',
            'provider_id.exists' => 'Provider not found.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
