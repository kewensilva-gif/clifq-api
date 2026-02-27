<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;

class LoanEquipamentPolicy
{
    public function viewAny(User $user): bool
    {
        $allowedRoles = [RoleEnum::ADMIN, RoleEnum::SECRETARY];
        return in_array($user->role_id, $allowedRoles);
    }

    public function update(User $user): bool
    {
        $allowedRoles = [RoleEnum::ADMIN, RoleEnum::SECRETARY];
        return in_array($user->role_id, $allowedRoles);
    }

    public function delete(User $user): bool
    {
        $allowedRoles = [RoleEnum::ADMIN];
        return in_array($user->role_id, $allowedRoles);
    }
}
