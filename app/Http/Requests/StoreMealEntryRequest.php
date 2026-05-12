<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMealEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $mealEntry = $this->route('meal_entry');

        return [
            'user_id' => ['required', 'exists:users,id'],
            'meal_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'meal_type' => ['required', 'in:breakfast,lunch,dinner,custom'],
            'meal_rate' => ['required', 'numeric', 'min:0', 'max:99999'],
            'quantity' => ['required', 'numeric', 'min:0.5', 'max:3'],
            'note' => ['nullable', 'string', 'max:255'],
            'is_guest' => ['boolean'],
            'guest_name' => ['nullable', 'required_if:is_guest,true', 'string', 'max:100'],
            'status' => ['in:pending,approved,rejected'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a user.',
            'meal_date.required' => 'Meal date is required.',
            'meal_date.before_or_equal' => 'Meal date cannot be in the future.',
            'meal_type.required' => 'Please select a meal type.',
            'meal_type.in' => 'Invalid meal type selected.',
            'quantity.min' => 'Minimum quantity is 0.5 (half meal).',
            'quantity.max' => 'Maximum quantity is 3.',
            'guest_name.required_if' => 'Guest name is required for guest meals.',
        ];
    }
}
