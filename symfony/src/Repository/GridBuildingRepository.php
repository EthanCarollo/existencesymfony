<?php

namespace App\Repository;

use App\Entity\GridBuilding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GridBuilding>
 */
class GridBuildingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GridBuilding::class);
    }

    public function findByUser($user)
    {
        return $this->createQueryBuilder('gb')
            ->join('gb.grid', 'g')
            ->andWhere('g.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
