<?php

namespace App\Repository;

use App\Entity\Pregnancy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pregnancy>
 */
class PregnancyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pregnancy::class);
    }

    public function getOrCreateCurrent(): Pregnancy
    {
        $pregnancy = $this->findOneBy([]);
        
        if (!$pregnancy) {
            $pregnancy = new Pregnancy();
            $this->getEntityManager()->persist($pregnancy);
            $this->getEntityManager()->flush();
        }
        
        return $pregnancy;
    }
}
