<?php

namespace App\Service\rapports;

use App\Dto\utils\OrderCriteria;
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
    
    public function getAll(OrderCriteria $criteria): array
    {
        return $this->repository->findAllActive($criteria);
    }

    /**
     * Récupérer par type
     */
    public function getByType(TypeCalendriers $type, OrderCriteria $criteria): array
    {
        return $this->repository->findByType($type, $criteria);
    }

    /**
     * Récupérer entre deux dates
     */
    public function getBetweenDates(
        \DateTimeInterface $debut,
        \DateTimeInterface $fin,
        OrderCriteria $criteria
    ): array {
        return $this->repository->findBetweenDates($debut, $fin, $criteria);
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
        if ($dateDebut > $dateFin) {
            throw new \InvalidArgumentException("La date de début doit être antérieure ou egal à la date de fin.");
        }   
        $calendrier->setDateDebut($dateDebut);
        $calendrier->setDateFin($dateFin);
        $calendrier->setTypeCalendriers($type);

        $this->em->persist($calendrier);
        $this->em->flush();

        return $calendrier;
    }
    public function toArrayList(array $calendriers,array $exclude = []): array
    {
        $result = [];

        foreach ($calendriers as $index => $calendrier) {
            $result[$index] = $calendrier->toArray($exclude);
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
    public function getDate(
        \DateTimeInterface $date,
        OrderCriteria $criteria
    ): array {
        return $this->repository->findDate($date, $criteria);
    }
    public function updateCalendrier(Calendriers $calendrier,CalendrierDto $dto ): Calendriers
    {
        $calendrier->setDateDebut($dto->getDateDebut());
        $calendrier->setDateFin($dto->getDateFin());
        $typeCalendrier = $this->typeCalendriersService->getById($dto->getTypeCalendrierId());
        if (!$typeCalendrier) {
            throw new \Exception('Type de calendrier non trouvé pour id ' . $dto->getTypeCalendrierId());
        }
        $calendrier->setTypeCalendriers($typeCalendrier);
        $this->em->persist($calendrier);
        $this->em->flush();
        return $calendrier;
    }
    public function updateCalendrierDto(int $idCalendrier , CalendrierDto $calendrierDto): Calendriers
    {
        $calendrier = $this->getById($idCalendrier);
        if (!$calendrier) {
            throw new \Exception('Calendrier non trouvé pour id ' . $idCalendrier);
        }
        return $this->updateCalendrier($calendrier, $calendrierDto);
    }
    public function deleted(int $idCalendrier): void
    {
        $calendrier = $this->getById($idCalendrier);
        if (!$calendrier) {
            throw new \Exception("Calendrier non trouvé pour id $idCalendrier");
        }
        $calendrier->setDeletedAt(new \DateTimeImmutable());
        $this->em->persist($calendrier);
        $this->em->flush();
    }


    
}