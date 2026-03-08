<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ImageChoice
{
    public string $label = 'Importer un fichier';
    public mixed $imagefile;
    public mixed $imageurl = null;
}
