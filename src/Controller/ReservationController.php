<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/reservation', name: 'api_reservation_')]
class ReservationController extends AbstractController
{
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);

            // Validation
            if (empty($data['articleId']) || empty($data['name']) || empty($data['email'])) {
                return $this->json([
                    'error' => 'Missing required fields: articleId, name and email',
                ], 400);
            }

            // Valider le format de l'email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->json([
                    'error' => 'Invalid email format',
                ], 400);
            }

            // Récupérer l'article
            $article = $articleRepository->find($data['articleId']);
            if (!$article) {
                return $this->json([
                    'error' => 'Article not found',
                ], 404);
            }

            // Créer la réservation
            $reservation = new Reservation();
            $reservation->setArticle($article);
            $reservation->setName($data['name']);
            $reservation->setEmail($data['email']);
            $reservation->setMessage($data['message'] ?? null);

            // Sauvegarder
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Reservation created successfully',
                'reservationId' => $reservation->getId(),
            ], 201);

        } catch (\Exception $e) {
            return $this->json([
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
