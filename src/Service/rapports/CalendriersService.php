<?php

namespace App\Service\rapports;

use App\Entity\rapports\Calendriers;
use App\Entity\rapports\TypeCalendriers;
use App\Repository\rapports\CalendriersRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\rapports\CalendrierDto;
use App\Service\rapports\TypeCalendriersService;

class CalendriersService
{
    private CalendriersRepository $repository;
    private EntityManagerInterface $em;
    private TypeCalendriersService $typeCalendriersService;

    public function __construct(
        CalendriersRepository $repository,
        EntityManagerInterface $em,
        TypeCalendriersService $typeCalendriersService
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->typeCalendriersService = $typeCalendriersService;
    }

    /**
     * Récupérer tous les calendriers actifs
     */
    
    public function getAllActive(string $order = 'DESC'): array
    {
        return $this->repository->findAllActive($order);
    }

    /**
     * Récupérer par type
     */
    public function getByType(TypeCalendriers $type, string $order = 'DESC'): array
    {
        return $this->repository->findByType($type, $order);
    }

    /**
     * Récupérer entre deux dates
     */
    public function getBetweenDates(
        \DateTimeInterface $debut,
        \DateTimeInterface $fin,
        string $order = 'ASC'
    ): array {
        return $this->repository->findBetweenDates($debut, $fin, $order);
    }

    /**
     * Récupérer par ID
     */
    public function getById(int $id): ?Calendriers
    {
        return $this->repository->findActiveById($id);
    }

    /**
     * Créer un calendrier
     */
    public function insert(
        \DateTimeInterface $dateDebut,
        \DateTimeInterface $dateFin,
        TypeCalendriers $type
    ): Calendriers {
        $calendrier = new Calendriers();
        $calendrier->setDateDebut($dateDebut);
        $calendrier->setDateFin($dateFin);
        $calendrier->setTypeCalendriers($type);

        $this->em->persist($calendrier);
        $this->em->flush();

        return $calendrier;
    }
    public function toArrayList(array $calendriers): array
    {
        $result = [];

        foreach ($calendriers as $index => $calendrier) {
            $result[$index] = $calendrier->toArray();
        }

        return $result;
    }
    public function insertDto(CalendrierDto $dto): Calendriers
    {
        $calendrier = $dto->toEntity();
        $typeCalendrier = $this->typeCalendriersService->getById($dto->getTypeCalendrierId());
        if (!$typeCalendrier) {
            throw new \Exception('Type de calendrier non trouvé pour id ' . $dto->getTypeCalendrierId());
        }
        $calendrier->setTypeCalendriers($typeCalendrier);
        $this->em->persist($calendrier);
        $this->em->flush();
        return $calendrier;
    }
}