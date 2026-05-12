<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasRole(['super_admin', 'manager']) ?? false;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                $isUpdate ? "unique:users,email,{$userId}" : 'unique:users,email',
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'employee_id' => [
                'nullable',
                'string',
                'max:50',
                $isUpdate ? "unique:users,employee_id,{$userId}" : 'unique:users,employee_id',
            ],
            'department' => ['nullable', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'password' => $isUpdate
                ? ['nullable', Password::min(8)->mixedCase()->numbers()]
                : ['required', Password::min(8)->mixedCase()->numbers()],
            'password_confirmation' => $isUpdate ? ['nullable', 'same:password'] : ['required', 'same:password'],
            'role' => ['required', 'exists:roles,name'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'meal_active' => ['boolean'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
