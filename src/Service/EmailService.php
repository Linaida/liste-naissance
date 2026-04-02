<?php

namespace App\Service;

use App\Entity\Reservation;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private string $fromEmail,
        private string $fromName,
    ) {}

    /**
     * Envoyer un email de confirmation de réservation
     */
    public function sendReservationConfirmation(Reservation $reservation): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to(new Address($reservation->getEmail(), $reservation->getName()))
            ->subject("Confirmation de réservation - {$reservation->getArticle()->getName()}")
            ->htmlTemplate('emails/reservation_confirmation.html.twig')
            ->context([
                'reservation' => $reservation,
                'article' => $reservation->getArticle(),
            ]);

        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Vous pouvez logger l'erreur ici
            // Mais ne pas lancer l'exception pour ne pas bloquer la création de la réservation
            error_log("Erreur envoi email: " . $e->getMessage());
        }
    }

    /**
     * Envoyer un email d'annulation de réservation
     */
    public function sendReservationCancellation(Reservation $reservation): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to(new Address($reservation->getEmail(), $reservation->getName()))
            ->subject("Annulation de réservation - {$reservation->getArticle()->getName()}")
            ->htmlTemplate('emails/reservation_cancellation.html.twig')
            ->context([
                'reservation' => $reservation,
                'article' => $reservation->getArticle(),
            ]);

        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            error_log("Erreur envoi email: " . $e->getMessage());
        }
    }
}
