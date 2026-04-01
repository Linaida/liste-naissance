<?php

namespace App\Enum;

enum ArticleCategory: string
{
    case FOOD = 'food';
    case HYGIENE = 'hygiene';
    case TOY = 'toy';
    case BED = 'bed';
    case FURNITURE = 'furniture';
    case TRANSPORT = 'transport';
    case CLOTHES = 'clothes';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::FOOD => 'Alimentation',
            self::HYGIENE => 'Hygiène',
            self::TOY => 'Jouet',            
            self::BED => 'Literie',
            self::FURNITURE => 'Mobilier',
            self::TRANSPORT => 'Transport',
            self::CLOTHES => 'Vêtements',
            self::OTHER => '--- Autre ---',
        };
    }
}