<?php
namespace App\Entity\rapports;

use App\Entity\utils\BaseNom;
use App\Repository\rapports\TypeCalendriersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeCalendriersRepository::class)]
class TypeCalendriers extends BaseNom
{
    
}