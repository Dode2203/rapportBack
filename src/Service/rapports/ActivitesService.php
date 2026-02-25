<?php
namespace App\Service\rapports;

use App\Repository\rapports\ActivitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\rapports\CalendriersUtilisateurs;
use App\Service\rapports\EffectsImpactsService;
use App\Entity\rapports\Activites;

class ActivitesService
{
    private EntityManagerInterface $em;
    private ActivitesRepository $activitesRepository;

    private EffectsImpactsService $effectsImpactsService;
    
    public function __construct(EntityManagerInterface $em, ActivitesRepository $activitesRepository, EffectsImpactsService $effectsImpactsService)
    {
        $this->em = $em;
        $this->activitesRepository = $activitesRepository;
        $this->effectsImpactsService = $effectsImpactsService;
    }
    public function insert(Activites $activite): Activites
    {
        $this->em->persist($activite);
        $this->em->flush();
        return $activite;
    }
    public function findByCalendrierUtilisateur(CalendriersUtilisateurs $calendrierUtilisateur, string $order = 'DESC'): array
    {
        return $this->activitesRepository->findByCalendrierUtilisateur($calendrierUtilisateur, $order);
    }
    public function transformerArray(array $activites, array $exclude = []): array
    {
        $result = [];
        foreach ($activites as $index => $activite) {
            $effectsImpacts = $this->effectsImpactsService->getByActivite($activite);
            $result[$index] ['activite'] = $activite->toArray($exclude);
            $result[$index]['effectsImpacts'] = $this->effectsImpactsService->transformerArray($effectsImpacts, $exclude);
        }
        return $result;
    }
    
    
}