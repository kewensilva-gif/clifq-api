<?php

namespace App\Enums;

enum RoleEnum: int
{
    case ADMIN = 1;
    case SECRETARY = 2;
    case TECH = 3;

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::SECRETARY => 'Secretário(a)',
            self::TECH => 'Técnico(a)',
        };
    }

    public static function fromValue(int $value): ?self
    {
        return match($value) {
            1 => self::ADMIN,
            2 => self::SECRETARY,
            3 => self::TECH,
            default => null,
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($role) => [
                'label' => $role->label(),
                'value' => $role->value,
            ],
            self::cases()
        );
    }
}
