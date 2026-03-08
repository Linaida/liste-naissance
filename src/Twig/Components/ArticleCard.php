<?php

namespace App\Twig\Components;

use App\Entity\Article;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ArticleCard
{
    public Article $article;
}
