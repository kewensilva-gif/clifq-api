<?php

namespace App\Enums;

enum EquipamentEnum: int
{
    case DISPONIVEL = 0;
    case EMPRESTADO = 1;

    public function label(): string
    {
        return match($this) {
            self::DISPONIVEL => 'Disponível',
            self::EMPRESTADO => 'Emprestado'
        };
    }

    public static function fromValue(int $value): ?self
    {
        return match($value) {
            0 => self::DISPONIVEL,
            1 => self::EMPRESTADO,
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
