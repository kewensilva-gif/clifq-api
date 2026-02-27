<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthorizeLoanRequest extends FormRequest
{
    public function authorize()
    {
        $allowedRoles = [RoleEnum::ADMIN, RoleEnum::SECRETARY];
        return in_array(auth()->user()->role_id, $allowedRoles);
    }


    public function rules()
    {
        $userId = auth()->id();

        return [
            'id_secretary' => ['required', 'integer', Rule::in([$userId])],
            'status' => 'required|in:2,3',
        ];
    }
}
