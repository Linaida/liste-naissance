<?php

namespace App\Controller;

use App\DTO\FilterDTO;
use App\DTO\OrderDTO;
use App\DTO\PaginationDTO;
use App\Enum\ArticleCategory;
use App\Repository\ArticleRepository;
use App\Repository\PregnancyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(PregnancyRepository $pregnancyRepository): Response
    {
        $pregnancy = $pregnancyRepository->find(2); // Example ID, replace with actual logic
        $semaineGrossesse = (new \DateTime())->diff($pregnancy->getStartDate())->format('%a') / 7;
        return $this->render('home/index.html.twig', [
            'semaineActuelle' => $semaineGrossesse,
            'termePrevu' => $pregnancy->getEndDate()->format('Y-m-d'),
        ]);
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
