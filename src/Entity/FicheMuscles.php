<?php

namespace App\Entity;

use App\Repository\FicheMusclesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheMusclesRepository::class)]
class FicheMuscles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne (cascade: ["remove"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?FicheContenu $Fiche_Contenu = null;

    #[ORM\OneToOne (cascade: ["remove"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Muscles $Muscles = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFicheContenu(): ?fichecontenu
    {
        return $this->Fiche_Contenu;
    }

    public function setFicheContenu(fichecontenu $Fiche_Contenu): static
    {
        $this->Fiche_Contenu = $Fiche_Contenu;

        return $this;
    }

    public function getMuscles(): ?muscles
    {
        return $this->Muscles;
    }

    public function setMuscles(muscles $Muscles): static
    {
        $this->Muscles = $Muscles;

        return $this;
    }
}
