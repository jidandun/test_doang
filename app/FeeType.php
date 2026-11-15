<?php

namespace App;

enum FeeType: string
{
    case Gratis = 'gratis';
    case Berbayar = 'berbayar';

    public function label(): string
    {
        return match ($this) {
            self::Gratis => 'Gratis',
            self::Berbayar => 'Berbayar',
        };
    }
}
