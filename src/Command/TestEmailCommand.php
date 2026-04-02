<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:test:email',
    description: 'Envoie un email de test via MailPit'
)]
class TestEmailCommand extends Command
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $dateTime = date('Y-m-d H:i:s');
            $html = <<<HTML
                <h1>Coucou ! 👋</h1>
                <p>Cet email de test confirme que votre service email fonctionne correctement.</p>
                <p><strong>Date/Heure test :</strong> {$dateTime}</p>
                <hr>
                <p>✅ Si vous voyez cet email dans MailPit, tout marche !</p>
                <p>Rendez-vous sur http://localhost:8025 pour vérifier</p>
            HTML;

            $email = (new Email())
                ->from(new Address('noreply@liste-naissance.local', 'Liste de Naissance'))
                ->to('test@example.com')
                ->subject('🧪 Email de Test depuis MailPit')
                ->html($html);

            $this->mailer->send($email);
            $io->success('✅ Email envoyé avec succès ! Vérifiez http://localhost:8025');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('❌ Erreur lors de l\'envoi : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
