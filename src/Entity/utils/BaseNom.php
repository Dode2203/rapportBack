<?php

namespace App\Entity\utils;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class BaseNom extends BaseEntite
{
    #[ORM\Column(type: "string", length: 255)]
    protected ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    // Si tu veux inclure le nom dans le toArray() hérité
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['name'] = $this->getName();
        return $data;
    }
}