<?php

namespace App\Repository\rapports;

use App\Entity\rapports\Calendriers;
use App\Entity\rapports\TypeCalendriers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Dto\utils\OrderCriteria;
class CalendriersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendriers::class);
    }

    /**
     * Trouver tous les calendriers non supprimés
     */
public function findAllActive(OrderCriteria $criteria = new OrderCriteria()): array
{
    return $this->createQueryBuilder('c')
        ->andWhere('c.deletedAt IS NULL')
        ->orderBy('c.' . $criteria->getField(), $criteria->getDirection())
        ->getQuery()
        ->getResult();
}

    /**
     * Trouver par type de calendrier
     */
    public function findByType(TypeCalendriers $type, OrderCriteria $criteria = new OrderCriteria()): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.typeCalendriers = :type')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('type', $type)
            ->orderBy('c.' . $criteria->getField(), $criteria->getDirection())
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les calendriers entre deux dates
     */
    public function findBetweenDates(
        \DateTimeInterface $debut,
        \DateTimeInterface $fin,
        OrderCriteria $criteria = new OrderCriteria()
    ): array {
        return $this->createQueryBuilder('c')
            ->andWhere('c.dateDebut >= :debut')
            ->andWhere('c.dateFin <= :fin')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->orderBy('c.' . $criteria->getField(), $criteria->getDirection())
            ->getQuery()
            ->getResult();
    }
    public function findDate(
        \DateTimeInterface $date,
        OrderCriteria $criteria = new OrderCriteria()
    ): array {
        return $this->createQueryBuilder('c')
            ->andWhere('c.dateDebut <= :date')
            ->andWhere('c.dateFin >= :date')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('date', $date)
            ->orderBy('c.' . $criteria->getField(), $criteria->getDirection())
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
    public function findBetweenDatesDebut(
        \DateTimeInterface $debut,
        \DateTimeInterface $fin,
        OrderCriteria $criteria = new OrderCriteria()
    ): array {
        return $this->createQueryBuilder('c')
            ->andWhere('c.dateDebut >= :debut')
            ->andWhere('c.dateDebut <= :fin')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->orderBy('c.' . $criteria->getField(), $criteria->getDirection())
            ->getQuery()
            ->getResult();
    }
    
}