<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;

class LoansEquipamentStoreRequest extends FormRequest
{
   public function authorize(): bool
   {
       $allowedRoles = [RoleEnum::ADMIN, RoleEnum::SECRETARY, RoleEnum::TECH];
       return in_array(auth()->user()->role_id, $allowedRoles);
   }

   public function rules(): array
   {
        return [
           'id_requester' => ['required'],
           'id_equipament' => ['required'],
           'justification' => ['required']
        ];
   }
}
