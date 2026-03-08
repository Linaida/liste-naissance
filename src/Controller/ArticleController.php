<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\Type\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class ArticleController extends AbstractController
{
    public function __construct(private ArticleRepository $articleRepository) {}

    #[Route('/articles', name: 'articles_list', methods: ['GET'])]
    public function listArticles(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $this->articleRepository->findAll(),
        ]);
    }

    #[Route('/articles/new', name: 'articles_new', methods: ['GET', 'POST'])]
    public function newArticle(Request $request): Response
    {
        $form = $this->createForm(ArticleType::class, new Article());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $imageFile = $form->get('imageFile')->getData();
            $imageUrl  = $form->get('imageUrl')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );

                $article->setImagePath($newFilename);
            } elseif ($imageUrl) {
                $article->setImagePath($imageUrl);
            }
            // Save the article to the database
            $this->articleRepository->save($article, true);

            return $this->redirectToRoute('articles_list');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/articles/{id}/edit', name: 'articles_edit', methods: ['GET', 'POST'])]
    public function editArticle(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            $imageUrl  = $form->get('imageUrl')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );

                $article->setImagePath($newFilename);
            } elseif ($imageUrl) {
                $article->setImagePath($imageUrl);
            }
            // Save the article to the database
            $this->articleRepository->save($article, true);

            return $this->redirectToRoute('articles_list');
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form,
            'article' => $article,
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
