<?php

namespace App\Entity\utilisateurs;

use App\Repository\UtilisateursRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\utils\BaseEntite;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
class Utilisateurs extends BaseEntite
{
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    #[ORM\Column(length: 255)]
    private ?string $entite = null;


    #[ORM\ManyToOne(targetEntity: Roles::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Roles $role = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;
        return $this;
    }

    public function getEntite(): ?string
    {
        return $this->entite;
    }

    public function setEntite(string $entite): static
    {
        $this->entite = $entite;
        return $this;
    }


    public function getRole(): ?Roles
    {
        return $this->role;
    }

    public function setRole(?Roles $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function toArray(array $exclude = []): array
    {
        $data = parent::toArray($exclude);
        // $data['entite'] = $this->getEntite();
        $data['role'] = $this->getRole() ? $this->getRole()->getName() : null;
        return $data;
    }
}
