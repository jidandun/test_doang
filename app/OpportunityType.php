<?php

namespace App;

enum OpportunityType: string
{
    case Lomba = 'lomba';
    case Beasiswa = 'beasiswa';

    public function label(): string
    {
        return match ($this) {
            self::Lomba => 'Lomba',
            self::Beasiswa => 'Beasiswa',
        };
    }
}
