<?php

namespace App\Controller;

use App\DTO\FilterDTO;
use App\DTO\OrderDTO;
use App\DTO\PaginationDTO;
use App\Enum\ArticleCategory;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/liste-naissance', name: 'birthlist')]
    public function birthlist(Request $request, ArticleRepository $repo): Response
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
        }else{
            $pagination->orders[] = new OrderDTO('price', 'ASC');
        }

        $articles = $repo->findBySearch($pagination);

        return $this->render('birthlist/index.html.twig', [
            'articles' => $articles,
            'categories' => ArticleCategory::cases()
        ]);
    }
}
