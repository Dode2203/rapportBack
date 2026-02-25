<?php

namespace App\Dto\rapports;

use App\Entity\rapports\EffectsImpacts;

class EffectImpactDto
{
    public ?string $effect = null;
    public ?string $impact = null;

    public function getEffect(): ?string
    {
        return $this->effect;
    }

    public function setEffect(?string $effect): self
    {
        $this->effect = $effect;
        return $this;
    }

    public function getImpact(): ?string
    {
        return $this->impact;
    }

    public function setImpact(?string $impact): self
    {
        $this->impact = $impact;
        return $this;
    }
    public function getEffectsImpactsClass(): EffectsImpacts
    {
        $result = new EffectsImpacts();
        $result->setEffect($this->effect);
        $result->setImpact($this->impact);
        return $result;
    }
}
