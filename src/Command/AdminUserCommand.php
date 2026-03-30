<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'admin:user',
    description: 'Permet de gérer les utilisateurs administrateurs',
)]
class AdminUserCommand extends Command
{
    public function __construct(private UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'Email de l\'utiliateur')
            ->addArgument('password', InputArgument::OPTIONAL, 'Mot de passe de l\'utiliateur')
            ->addOption('add', null, InputOption::VALUE_NONE, 'Action à effectuer')
            ->addOption('get-password', null, InputOption::VALUE_NONE, 'Récupérer le mot de passe')
            ->addOption('update-password', null, InputOption::VALUE_NONE, 'Mettre à jour le mot de passe')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if ($input->getOption('add')) {
            return $this->addUser($email, $password, $io);
        } 

        if ($input->getOption('get-password')) {
            return $this->getPassword($email, $io);
        }

        if ($input->getOption('update-password')) {
            return $this->updatePassword($email, $password, $io);
        }

        $io->warning('Aucune action spécifiée. Utilisez --add pour ajouter un utilisateur.');
        return Command::FAILURE;
    }

    private function addUser(string $email, string $password, SymfonyStyle $io): int
    {
        if (!$email || !$password) {
            $io->error('Veuillez fournir un email et un mot de passe pour ajouter un utilisateur.');
            return Command::FAILURE;
        }
        // Vérifier si l'utilisateur existe déjà
        if ($this->userRepository->findOneBy(['email' => $email])) {
            $io->error('Un utilisateur avec cet email existe déjà.');
            return Command::FAILURE;
        }
        // Ajouter l'utilisateur
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_ADMIN']);
        $this->userRepository->save($user, true);

        $io->success(sprintf('Utilisateur ajouté : %s', $email));
        return Command::SUCCESS;
    }

    private function getPassword(string $email, SymfonyStyle $io): int
    {
        if (!$email) {
            $io->error('Veuillez fournir un email pour récupérer le mot de passe.');
            return Command::FAILURE;
        }
        // Récupérer l'utilisateur par email
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            $io->error('Utilisateur non trouvé.');
            return Command::FAILURE;        
        }
        $io->success(sprintf('Mot de passe hashé pour %s : %s', $email, $user->getPassword()));
        return Command::SUCCESS;
    }

    private function updatePassword(string $email, string $password, SymfonyStyle $io): int
    {
        if (!$email || !$password) {
            $io->error('Veuillez fournir un email et un mot de passe pour mettre à jour le mot de passe.');
            return Command::FAILURE;
        }
        // Récupérer l'utilisateur par email
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            $io->error('Utilisateur non trouvé.');
            return Command::FAILURE;        
        }
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $this->userRepository->save($user, true);
        $io->success(sprintf('Mot de passe mis à jour pour %s', $email));
        return Command::SUCCESS;
    }

}
