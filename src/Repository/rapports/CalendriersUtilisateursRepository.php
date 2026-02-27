<?php

namespace App\Repository\rapports;

use App\Entity\rapports\CalendriersUtilisateurs;
use App\Entity\rapports\Calendriers;
use App\Entity\utilisateurs\Utilisateurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CalendriersUtilisateursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalendriersUtilisateurs::class);
    }

    /**
     * Trouver par utilisateur
     * @return CalendriersUtilisateurs[]
     */
    public function findByUtilisateur(Utilisateurs $utilisateur, string $order = 'DESC'): array
    {
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        return $this->createQueryBuilder('cu')
            ->andWhere('cu.utilisateur = :utilisateur')
            ->andWhere('cu.deletedAt IS NULL')
            ->setParameter('utilisateur', $utilisateur)
            ->orderBy('cu.createdAt', $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver par calendrier
     * @return CalendriersUtilisateurs[]
     */
    public function findByCalendrier(Calendriers $calendrier, string $order = 'DESC'): array
    {
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        return $this->createQueryBuilder('cu')
            ->andWhere('cu.calendrier = :calendrier')
            ->andWhere('cu.deletedAt IS NULL')
            ->setParameter('calendrier', $calendrier)
            ->orderBy('cu.createdAt', $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifier si un utilisateur est déjà assigné à un calendrier
     */
    public function findOneByUtilisateurAndCalendrier(Utilisateurs $utilisateur, Calendriers $calendrier): ?CalendriersUtilisateurs
    {
        return $this->createQueryBuilder('cu')
            ->andWhere('cu.utilisateur = :utilisateur')
            ->andWhere('cu.calendrier = :calendrier')
            ->andWhere('cu.deletedAt IS NULL')
            ->setParameter('utilisateur', $utilisateur)
            ->setParameter('calendrier', $calendrier)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findActiveById(int $id): ?CalendriersUtilisateurs
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id = :id')
            ->andWhere('t.deletedAt IS NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}