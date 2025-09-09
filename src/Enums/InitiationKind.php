<?php

namespace Bazegel\Monobank\Enums;

class InitiationKind
{
    public const MERCHANT = 'merchant';
    public const CLIENT = 'client';

    public static function values(): array
    {
        return [
            self::MERCHANT,
            self::CLIENT,
        ];
    }
}
