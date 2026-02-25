<?php

namespace App\Dto\rapports;

use App\Entity\rapports\Activites;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Validator\Constraints\Count;

class ActiviteDto
{
    #[Assert\NotBlank(message: "L'activite est obligatoire.")]
    public ?string $activite = null;

    /** @var EffectImpactDto[] */
    #[Assert\NotNull(message: "La liste des activités ne peut pas être nulle.")]
    #[Count(min: 1, minMessage: "Vous devez fournir au moins une activité.")]
    public array $effectsImpacts = [];

    public function getActivite(): ?string
    {
        return $this->activite;
    }

    public function setActivite(?string $activite): self
    {
        $this->activite = $activite;
        return $this;
    }

    /**
     * @return EffectImpactDto[]
     */
    public function getEffectsImpacts(): array
    {
        return $this->effectsImpacts;
    }

    /**
     * @param EffectImpactDto[] $effectsImpacts
     */
    public function setEffetsImpacts(array $effetsImpacts): self
    {
        $this->effetsImpacts = $effetsImpacts;
        return $this;
    }

    /**
     * Ajouter un effetImpact
     */
    public function addEffectImpact(EffectImpactDto $effectImpact): self
    {
        $this->effectsImpacts[] = $effectImpact;
        return $this;
    }
    public function getActiviteClass(): Activites
    {
        $result = new Activites();
        $result->setName($this->activite);
        return $result;
    }
}