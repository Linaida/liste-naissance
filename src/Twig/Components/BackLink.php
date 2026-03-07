<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class BackLink
{
    public string $href;
    public string $label = 'Retour';
}
