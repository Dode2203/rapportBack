<?php

namespace App\Dto\rapports;

use App\Entity\rapports\Activites;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Validator\Constraints\Count;

class ActiviteDto
{
    #[Assert\NotBlank(message: "L'activite est obligatoire.")]
    public ?EffectImpactDto $activite ;

    /** @var EffectImpactDto[] */
    #[Assert\NotNull(message: "La liste des activités ne peut pas être nulle.")]
    #[Count(min: 1, minMessage: "Vous devez fournir au moins une activité.")]

    /** @var EffectImpactDto[] */
    #[Assert\NotNull]
    public array $effects = [];
    /** @var EffectImpactDto[] */
    #[Assert\NotNull]
    public array $impacts = [];

    public function getActivite(): ?EffectImpactDto
    {
        return $this->activite;
    }

    public function setActivite(?EffectImpactDto $activite): self
    {
        $this->activite = $activite;
        return $this;
    }

    /**
     * @return EffectImpactDto[]
     */
    public function getEffects(): array
    {
        return $this->effects;
    }
    
    public function getImpacts(): array
    {
        return $this->impacts;
    }

    /**
     * @param EffectImpactDto[] $effects
     */
    public function setEffects(array $effects): self
    {
        $this->effects = $effects;
        return $this;
    }
    public function setImpacts(array $impacts): self
    {
        $this->impacts = $impacts;
        return $this;
    }

    /**
     * Ajouter un effet
     */
    public function addEffect(EffectImpactDto $effectImpactDto): self
    {
        $this->effects[] = $effectImpactDto;
        return $this;
    }
    public function addImpact(EffectImpactDto $effectImpactDto): self
    {
        $this->impacts[] = $effectImpactDto;
        return $this;
    }
    public function getActiviteClass(): Activites
    {
        $result = new Activites();
        $result->setName($this->activite->getName());
        return $result;
    }
}