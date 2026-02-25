<?php

namespace App\Repository\rapports;

use App\Entity\rapports\Calendriers;
use App\Entity\rapports\TypeCalendriers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CalendriersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendriers::class);
    }

    /**
     * Trouver tous les calendriers non supprimés
     */
    public function findAllActive(string $order = 'DESC'): array
    {
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        return $this->createQueryBuilder('c')
            ->andWhere('c.deletedAt IS NULL')
            ->orderBy('c.dateDebut', $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver par type de calendrier
     */
    public function findByType(TypeCalendriers $type, string $order = 'DESC'): array
    {
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        return $this->createQueryBuilder('c')
            ->andWhere('c.typeCalendriers = :type')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('type', $type)
            ->orderBy('c.dateDebut', $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les calendriers entre deux dates
     */
    public function findBetweenDates(
        \DateTimeInterface $debut,
        \DateTimeInterface $fin,
        string $order = 'ASC'
    ): array {
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        return $this->createQueryBuilder('c')
            ->andWhere('c.dateDebut >= :debut')
            ->andWhere('c.dateFin <= :fin')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->orderBy('c.dateDebut', $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver un calendrier actif par ID
     */
    public function findActiveById(int $id): ?Calendriers
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}