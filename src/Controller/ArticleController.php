<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/administration/articles', name: 'admin_articles_index')]
    public function adminArticles(ArticleRepository $articleRepository): Response
    {
        return $this->render('administration/article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
