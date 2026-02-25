<?php

namespace App\Service\rapports;

use App\Dto\rapports\ActiviteCollectionDto;
use App\Entity\rapports\CalendriersUtilisateurs;
use App\Entity\rapports\Calendriers;
use App\Entity\utilisateurs\Utilisateurs;
use App\Entity\rapports\Activites;
use App\Entity\rapports\EffectsImpacts;
use App\Repository\rapports\CalendriersUtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

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

                foreach ($activiteDto->getEffectsImpacts() as $effetImpactDto) {
                    // throw new Exception(var_dump($effetImpactDto));
                    $effetImpact = $effetImpactDto->getEffectsImpactsClass();
                    $effetImpact->setActivite($activite);

                    $this->effectsImpactsService->insert($effetImpact);
                }
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
    

}