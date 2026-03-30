<?php

namespace App\Controller;

use App\DTO\FilterDTO;
use App\DTO\OrderDTO;
use App\DTO\PaginationDTO;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleSearchController extends AbstractController
{
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
