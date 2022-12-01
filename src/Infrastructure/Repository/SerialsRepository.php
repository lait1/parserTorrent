<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Serials;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serials>
 *
 * @method Serials|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serials|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serials[]    findAll()
 * @method Serials[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerialsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serials::class);
    }

    public function save(Serials $serials): void
    {
        $this->getEntityManager()->persist($serials);
        $this->getEntityManager()->flush();

    }
}
