<?php

namespace App\Service\rapports;

use App\Dto\rapports\ActiviteCollectionDto;
use App\Entity\rapports\CalendriersUtilisateurs;
use App\Entity\rapports\Calendriers;
use App\Entity\utilisateurs\Utilisateurs;
use App\Repository\rapports\CalendriersUtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\rapports\Activites;
use Exception;
use App\Dto\utils\OrderCriteria;

class CalendriersUtilisateursService
{
    private CalendriersUtilisateursRepository $repository;
    private EntityManagerInterface $em;
    private ActivitesService $activitesService;
    private CalendriersService $calendriersService;
    private EffectsImpactsService $effectsImpactsService;
    public function __construct(
        CalendriersUtilisateursRepository $repository,
        EntityManagerInterface $em,
        ActivitesService $activitesService,
        CalendriersService $calendriersService,
        EffectsImpactsService $effectsImpactsService
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->activitesService = $activitesService;
        $this->calendriersService = $calendriersService;
        $this->effectsImpactsService = $effectsImpactsService;
    }

    /**
     * Assigner un utilisateur à un calendrier
     */
    public function insert(Utilisateurs $utilisateur, Calendriers $calendrier): CalendriersUtilisateurs
    {
        $existing = $this->repository->findOneByUtilisateurAndCalendrier($utilisateur, $calendrier);
        if ($existing) {
            throw new \InvalidArgumentException("L'utilisateur est déjà assigné à ce calendrier.");
        }

        $cu = new CalendriersUtilisateurs();
        $cu->setUtilisateur($utilisateur);
        $cu->setCalendrier($calendrier);

        $this->em->persist($cu);
        $this->em->flush();

        return $cu;
    }
    public function getByCalendrierAndUtilisateur(Utilisateurs $utilisateur, Calendriers $calendrier): ?CalendriersUtilisateurs
    {
        return $this->repository->findOneByUtilisateurAndCalendrier($utilisateur, $calendrier);
    }

    public function getByUtilisateur(Utilisateurs $utilisateur, string $order = 'DESC'): array
    {
        return $this->repository->findByUtilisateur($utilisateur, $order);
    }

    public function getByCalendrier(Calendriers $calendrier, string $order = 'DESC'): array
    {
        return $this->repository->findByCalendrier($calendrier, $order);
    }
    public function transformerArray(array $calendrierUtilisateurs): array
    {
        $result = [];
        $exclude = ['deletedAt', 'createdAt'];
        foreach ($calendrierUtilisateurs as $index => $calendrierUtilisateur) {
            $activites = $this->activitesService->findByCalendrierUtilisateur($calendrierUtilisateur);
            $result[$index] = $calendrierUtilisateur->toArray($exclude);
            $result[$index]['activites'] = $this->activitesService->transformerArray($activites, $exclude);
        }
        return $result;
    }
    /**
     * Insert une liste d'effets d'impact
     */
    private function insertListeEffectImpactDto(array $effectImpactDtos,Activites $activite,int $typeEffectImpactId): void
    {
        foreach ($effectImpactDtos as $effectImpactDto) {
            $effectImpactDto->setActivite($activite);
            $this->effectsImpactsService->insertTypeId($effectImpactDto, $typeEffectImpactId);
        }
    }
    public function insertRapportDto(Utilisateurs $utilisateur, ActiviteCollectionDto $activiteCollectionDto): CalendriersUtilisateurs
    {
        $this->em->beginTransaction();

        try {
            $calendrier = $this->calendriersService->getById($activiteCollectionDto->getIdCalendrier());
            if (!$calendrier) {
                throw new Exception("Le calendrier n'existe pas pour id=" . $activiteCollectionDto->getIdCalendrier());
            }
            $calendrierUtilisateur = $this->insert($utilisateur, $calendrier);

            foreach ($activiteCollectionDto->getActivites() as $activiteDto) {
                $activite = $activiteDto->getActiviteClass();
                $activite->setCalendrierUtilisateur($calendrierUtilisateur);
                $activite = $this->activitesService->insert($activite);
                
                #Pour insert liste impact et effet
                $this->insertListeEffectImpactDto($activiteDto->getImpacts(), $activite, 1);
                $this->insertListeEffectImpactDto($activiteDto->getEffects(), $activite, 2);
            }

            $this->em->commit();

            return $calendrierUtilisateur;

        } catch (\Throwable $e) {
            $this->em->rollback();
            throw $e;
        }
    }
    public function getByCalendrierId(int $idCalendrier,string $order = 'DESC'): array
    {
        $calendrier = $this->calendriersService->getById($idCalendrier);
        if (!$calendrier) {
            throw new Exception("Le calendrier n'existe pas pour id=" . $idCalendrier);
        }
        return $this->getByCalendrier($calendrier, $order);
    }
    /**
     * Récupérer tous les calendriers disponibles pour un utilisateur si le calendrier n'est pas dans la liste des calendriers utilisateur
     */
    public function getAllCalendrierDisponible(Utilisateurs $utilisateurs,array $listeCalendriers):array
    {
        $result = array();
        foreach($listeCalendriers as $calendrier)
        {
            $calendrierUtilisateur = $this->getByCalendrierAndUtilisateur($utilisateurs, $calendrier);
            if (!$calendrierUtilisateur) {
                $result[] = $calendrier;
            }
        }
        return $result;
    }
    public function getCalendrierDisponibleDate(Utilisateurs $utilisateurs,\DateTimeInterface $dateDebut,\DateTimeInterface $dateFin,OrderCriteria $criteria):array
    {
        $calendriers = $this->calendriersService->getBetweenDates($dateDebut,$dateFin,$criteria);
        return $this->getAllCalendrierDisponible($utilisateurs,$calendriers);
    }
    public function getById(int $idCalendrierUtilisateur): CalendriersUtilisateurs
    {
        return $this->repository->findActiveById($idCalendrierUtilisateur);
    }
    public function validateCalendrierUtilsateur(int $idCalendrierUtilisateur): CalendriersUtilisateurs
    {
        $calendrierUtilisateur = $this->getById($idCalendrierUtilisateur);
        if (!$calendrierUtilisateur) {
            throw new Exception("Le calendrier utilisateur n'existe pas pour id=" . $idCalendrierUtilisateur);
        }
        $calendrierUtilisateur->setDateValidation(new \DateTime());
        $this->em->persist($calendrierUtilisateur);
        $this->em->flush();
        return $calendrierUtilisateur;
    }
    
    
    

}