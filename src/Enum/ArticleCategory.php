<?php

namespace App\Enum;

enum ArticleCategory: string
{
    case TOY = 'toy';
    case FURNITURE = 'furniture';
    case CLOTHES = 'clothes';
    case HYGIENE = 'hygiene';
    case TRANSPORT = 'transport';
    case FOOD = 'food';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::TOY => 'Jouet',
            self::FURNITURE => 'Mobilier',
            self::CLOTHES => 'Vêtements',
            self::HYGIENE => 'Hygiène',
            self::TRANSPORT => 'Transport',
            self::FOOD => 'Alimentation',
            self::OTHER => 'Autre',
        };
    }
}