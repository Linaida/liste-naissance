<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\Reservation;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test:reservation',
    description: 'Teste l\'envoi d\'emails pour une vraie réservation'
)]
class TestReservationCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailService $emailService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            // Récupérer un article sans réservation
            $articles = $this->entityManager->getRepository(Article::class)
                ->createQueryBuilder('a')
                ->leftJoin('a.reservations', 'r')
                ->where('r.id IS NULL')
                ->getQuery()
                ->getResult();

            if (empty($articles)) {
                $io->error('❌ Aucun article sans réservation trouvé dans la base de données');
                return Command::FAILURE;
            }

            $article = $articles[array_rand($articles)];

            // Créer une réservation de test
            $reservation = new Reservation();
            $reservation->setName('🧪 Test User');
            $reservation->setEmail('test@example.com');
            $reservation->setMessage('Ceci est une réservation de test pour vérifier l\'envoi d\'emails');
            $reservation->setArticle($article);

            // Enregistrer la réservation
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            // Envoyer les emails
            $this->emailService->sendReservationConfirmation($reservation);

            $io->success('✅ Réservation de test créée et emails envoyés !');
            $io->newLine();
            $io->section('Détails de la réservation');
            $io->text([
                "ID: {$reservation->getId()}",
                "Article: {$article->getName()}",
                "Client: {$reservation->getName()} ({$reservation->getEmail()})",
                "Prix: {$article->getPrice()}€",
            ]);
            $io->newLine();
            $io->text('💌 Vérifiez les emails dans MailPit: http://localhost:8025');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('❌ Erreur : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
