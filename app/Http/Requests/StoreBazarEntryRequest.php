<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBazarEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'entry_date' => ['required', 'date', 'before_or_equal:today'],
            'user_id' => ['required', 'exists:users,id'],
            'category_id' => ['nullable', 'exists:bazar_categories,id'],
            'vendor_id' => ['nullable', 'exists:bazar_vendors,id'],
            'item_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'unit' => ['nullable', 'string', 'max:50'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'receipt_number' => ['nullable', 'string', 'max:100'],
            'receipt_image' => ['nullable', 'image', 'max:4096'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
