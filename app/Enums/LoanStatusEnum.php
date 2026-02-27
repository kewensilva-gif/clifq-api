<?php

namespace App\Enums;

enum LoanStatusEnum: int
{
    case PENDENTE = 1;
    case AUTORIZADO = 2;
    case RECUSADO = 3;

    public function label(): string
    {
        return match($this) {
            self::PENDENTE => 'Pendente',
            self::AUTORIZADO => 'Autorizado',
            self::RECUSADO => 'Recusado',
        };
    }
    public function color(): string
    {
        return match($this) {
            self::PENDENTE => '#E5B769',
            self::AUTORIZADO => '#22C55E',
            self::RECUSADO => '#EF4444',
        };
    }

    public static function fromValue(int $value): ?self
    {
        return match($value) {
            1 => self::PENDENTE,
            2 => self::AUTORIZADO,
            3 => self::RECUSADO,
            default => null,
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($status) => [
                'label' => $status->label(),
                'value' => $status->value,
                'color' => $status->color()
            ],
            self::cases()
        );
    }
}
