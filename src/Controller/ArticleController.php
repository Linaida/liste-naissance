<?php

namespace App\Controller;

use App\DTO\FilterDTO;
use App\DTO\OrderDTO;
use App\DTO\PaginationDTO;
use App\Entity\Article;
use App\Form\Type\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

    #[Route('/article/{id}/delete', name: 'articles_delete', methods: ['POST'])]
    public function deleteArticle(Request $request, Article $article): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('CSRF token invalide.');
        }
        if ($article->getImagePath() && str_starts_with($article->getImagePath(), 'http') === false) {
            $imagePath = $this->getParameter('uploads_directory') . '/' . $article->getImagePath();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $this->articleRepository->remove($article, true);
        return $this->redirectToRoute('articles_list');
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/articles/search', name: 'articles_search')]
    public function search(Request $request, ArticleRepository $repo): Response
    {
        $pagination = new PaginationDTO();

        if ($category = $request->query->get('category')) {
            $pagination->filters[] = new FilterDTO('category', '=', $category);
        }

        if ($status = $request->query->get('status')) {
            $pagination->filters[] = new FilterDTO(
                'booked',
                '=',
                $status === 'booked'
            );
        }

        if ($sort = $request->query->get('sort')) {

            if ($sort === 'price_asc') {
                $pagination->orders[] = new OrderDTO('price', 'ASC');
            }

            if ($sort === 'price_desc') {
                $pagination->orders[] = new OrderDTO('price', 'DESC');
            }
        }

        $articles = $repo->findBySearch($pagination);

        return $this->render('birthlist/partials/_birth_list_frame.html.twig', [
            'articles' => $articles
        ]);
    }
}
