<?php

namespace App\Entity\rapports;
use App\Entity\utils\BaseEntite;
use App\Entity\rapports\Calendriers;
use App\Entity\utilisateurs\Utilisateurs;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: CalendriersUtilisateursRepository::class)]
class CalendriersUtilisateurs extends BaseEntite
{
    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateurs $utilisateur = null;
    
    #[ORM\ManyToOne(targetEntity: Calendriers::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Calendriers $calendrier = null;

    public function getUtilisateur(): ?Utilisateurs
    {
        return $this->utilisateur;
    }
    public function setUtilisateur(?Utilisateurs $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
    public function getCalendrier(): ?Calendriers
    {
        return $this->calendrier;
    }
    public function setCalendrier(?Calendriers $calendrier): self
    {
        $this->calendrier = $calendrier;
        return $this;
    }
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['utilisateur'] = $this->getUtilisateur()->toArray();
        $data['calendrier'] = $this->getCalendrier()->toArray();
        return $data;
    }
    
    
}