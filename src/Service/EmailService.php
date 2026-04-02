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
        private string $adminEmails,
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
            // Envoyer également une notification aux admins
            $this->notifyAdminsOfReservation($reservation);
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

    /**
     * Notifier les admins d'une nouvelle réservation
     */
    private function notifyAdminsOfReservation(Reservation $reservation): void
    {
        if (empty($this->adminEmails)) {
            return;
        }

        $adminEmailList = array_filter(
            array_map('trim', explode(';', $this->adminEmails)),
            fn($email) => !empty($email)
        );

        foreach ($adminEmailList as $adminEmail) {
            $email = (new TemplatedEmail())
                ->from(new Address($this->fromEmail, $this->fromName))
                ->to(new Address($adminEmail))
                ->subject("Nouvelle réservation - {$reservation->getArticle()->getName()}")
                ->htmlTemplate('emails/admin_reservation_notification.html.twig')
                ->context([
                    'reservation' => $reservation,
                    'article' => $reservation->getArticle(),
                ]);

            try {
                $this->mailer->send($email);
            } catch (\Exception $e) {
                error_log("Erreur envoi email admin: " . $e->getMessage());
            }
        }
    }
}
