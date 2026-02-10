<?php

namespace App\Enums;

enum StatusVendasEnum: int
{
    case PENDENTE = 0;
    case PAGO = 1;
    case CANCELADO = 2;
    case EM_ROTA = 3;
    case ENTREGUE = 4;
    
    public function label(): string
    {
        return match ($this) {
            self::PENDENTE => 'Pendente de Pagamento',
            self::PAGO => 'Pago',
            self::CANCELADO => 'Cancelado',
            self::EM_ROTA => 'Enviando',
            self::ENTREGUE => 'Entregue'
        };
    }

    public static function getList(): array
    {
        return array_map(fn ($enum) => [
            'id' => $enum->value,
            'value' => $enum->label(),
        ], self::cases());
    }
    
}