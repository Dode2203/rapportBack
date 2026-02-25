<?php

namespace App\Entity\utils;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
#[ORM\HasLifecycleCallbacks]
abstract class BaseEntite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(type: "datetime_immutable")]
    protected ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    protected ?\DateTimeImmutable $deletedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function delete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function restore(): void
    {
        $this->deletedAt = null;
    }

    // 🔥 Automatique à l’insertion
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Convertit l'entité en tableau simple (pour JSON)
     * Utilise la réflexion pour extraire les propriétés scalaires et les dates.
     */
    public function  
    toArray(): array
    {
        $reflection = new \ReflectionClass($this);
        $data = [];

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);

            // On vérifie si la propriété est initialisée pour éviter les erreurs 
            // sur les propriétés typées non nullables.
            if (!$property->isInitialized($this)) {
                continue;
            }

            $value = $property->getValue($this);

            // Formatage des dates
            if ($value instanceof \DateTimeInterface) {
                $data[$property->getName()] = $value->format('Y-m-d H:i:s');
                continue;
            }

            // On ignore les objets complexes (relations) pour éviter la circularité.
            // Le développeur doit surcharger toArray() s'il veut inclure des relations.
            if (is_object($value)) {
                continue;
            }

            $data[$property->getName()] = $value;
        }

        return $data;
    }
}
