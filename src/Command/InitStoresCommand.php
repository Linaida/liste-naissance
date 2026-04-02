<?php

namespace App\Command;

use App\Entity\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:init-stores',
    description: 'Initialise les plateformes de vente par défaut',
)]
class InitStoresCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $stores = [
            [
                'name' => 'Amazon',
                'searchUrlPattern' => 'https://www.amazon.fr/s?k={query}',
                'regex' => '/amazon\\.fr/i',
            ],
            [
                'name' => 'Aubert',
                'searchUrlPattern' => 'https://www.aubert.com/recherche?sfterm={query}',
                'regex' => '/aubert\\.com/i',
            ],
            [
                'name' => 'Vertbaudet',
                'searchUrlPattern' => 'https://www.vertbaudet.fr/search={query}.htm',
                'regex' => '/vertbaudet/i',
            ],
            [
                'name' => 'IKEA',
                'searchUrlPattern' => 'https://www.ikea.com/fr/fr/search?q={query}',
                'regex' => '/ikea\\.com/i',
            ],
            [
                'name' => 'Cdiscount',
                'searchUrlPattern' => 'https://www.cdiscount.com/search/10/{query}.html',
                'regex' => '/cdiscount\\.com/i',
            ],
            [
                'name' => 'Bébé9',
                'searchUrlPattern' => 'https://www.bebe9.com/catalogsearch/result/?q={query}',
                'regex' => '/bebe9\\.com/i',
            ],
        ];

        $io->writeln('Initialisation des plateformes...');

        foreach ($stores as $storeData) {
            // Vérifier si la plateforme existe déjà
            $existingStore = $this->em->getRepository(Store::class)->findOneBy(['name' => $storeData['name']]);

            if ($existingStore) {
                $io->writeln("<info>✓ {$storeData['name']} existe déjà</info>");
                continue;
            }

            $store = new Store();
            $store->setName($storeData['name']);
            $store->setSearchUrlPattern($storeData['searchUrlPattern']);
            $store->setRegex($storeData['regex']);
            $store->setActive(true);

            $this->em->persist($store);
            $io->writeln("<info>✓ {$storeData['name']} créée</info>");
        }

        $this->em->flush();
        $io->success('Plateformes initialisées avec succès !');

        return Command::SUCCESS;
    }
}
