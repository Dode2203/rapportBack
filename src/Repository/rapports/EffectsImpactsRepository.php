<?php

namespace App\Repository\rapports;

use App\Entity\rapports\EffectsImpacts;
use App\Entity\rapports\Activites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EffectsImpactsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EffectsImpacts::class);
    }

    public function findByActivite(
        Activites $activite,
        string $order = 'DESC'
    ): array {
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        return $this->createQueryBuilder('e')
            ->andWhere('e.activite = :activite')
            ->andWhere('e.deletedAt IS NULL')
            ->setParameter('activite', $activite)
            ->orderBy('e.createdAt', $order)
            ->getQuery()
            ->getResult();
    }
}