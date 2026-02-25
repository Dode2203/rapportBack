<?php

namespace App\Entity\rapports;

use App\Entity\utils\BaseEntite;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EffectsImpactsRepository::class)]
class EffectsImpacts extends BaseEntite
{

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $effect = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $impact = null;

    #[ORM\ManyToOne(targetEntity: Activites::class)]
    private ?Activites $activite = null;


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

    public function getActivite(): ?Activites
    {
        return $this->activite;
    }

    public function setActivite(?Activites $activite): self
    {
        $this->activite = $activite;
        return $this;
    }
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['effect'] = $this->getEffect();
        $data['impact'] = $this->getImpact();
        // $data['activite'] = $this->getActivite() ? $this->getActivite()->toArray() : [];
        return $data;
    }
}