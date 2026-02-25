<?php

namespace App\Service\rapports;

use App\Entity\rapports\Activites;
use App\Repository\rapports\EffectsImpactsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\rapports\EffectsImpacts;
class EffectsImpactsService
{
    private EffectsImpactsRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(
        EffectsImpactsRepository $repository,
        EntityManagerInterface $em
    ) {
        $this->repository = $repository;
        $this->em = $em;
    }
    public function insert(EffectsImpacts $effectImpact): EffectsImpacts
    {
        $this->em->persist($effectImpact);
        $this->em->flush();
        return $effectImpact;
    }
    
    public function getByActivite(Activites $activite, string $order = 'DESC'): array
    {
        return $this->repository->findByActivite($activite, $order);
    }
    public function transformerArray(array $effectsImpacts): array
    {
        $result = [];
        foreach ($effectsImpacts as $index => $effectImpact) {
            $result[$index] = $effectImpact->toArray();
        }
        return $result;
    }

    
}
