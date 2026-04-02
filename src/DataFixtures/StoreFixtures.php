<?php

namespace App\DataFixtures;

use App\Entity\Store;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StoreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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

        foreach ($stores as $storeData) {
            $store = new Store();
            $store->setName($storeData['name']);
            $store->setSearchUrlPattern($storeData['searchUrlPattern']);
            $store->setRegex($storeData['regex']);
            $store->setActive(true);

            $manager->persist($store);
        }

        $manager->flush();
    }
}
