<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;

class EquipamentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $allowedRoles = [RoleEnum::ADMIN, RoleEnum::SECRETARY, RoleEnum::TECH];
        return in_array(auth()->user()->role_id, $allowedRoles);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255', 'min:4'],
            'description' => ['required', 'max:1000', 'min:5'],
            'image' => ['required', 'file', 'image', 'mimes:jpeg,jpg,png']
        ];
    }
}
