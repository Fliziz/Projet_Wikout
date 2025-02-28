<?php

namespace App\Entity;

use App\Repository\FichesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FichesRepository::class)]
class Fiches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(max: 50, maxMessage: "Le nom ne doit pas dépasser 50 caractères.")]
    #[ORM\Column(length: 50)]
    private ?string $Nom = null;
    
    #[Assert\Length(max: 210, maxMessage: "La description ne doit pas dépasser 210 caractères.")]
    #[ORM\Column(type: 'text')]
    private ?string $Description = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Utilisateurs $Utilisateur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false )]
    private ?Categories $Categorie = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $Type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Difficulte $Difficulte = null;

    #[ORM\Column(length: 255)]
    private ?string $Photo = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $Editer = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getUtilisateur(): ?utilisateurs
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(?utilisateurs $Utilisateur): static
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }

    public function getCategorie(): ?categories
    {
        return $this->Categorie;
    }

    public function setCategorie(categories $Categorie): static
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getType(): ?type
    {
        return $this->Type;
    }

    public function setType(type $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getDifficulte(): ?difficulte
    {
        return $this->Difficulte;
    }

    public function setDifficulte(difficulte $Difficulte): static
    {
        $this->Difficulte = $Difficulte;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(string $Photo): static
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getEditer(): ?string
    {
        return $this->Editer;
    }

    public function setEditer(?string $Editer): static
    {
        $this->Editer = $Editer;

        return $this;
    }
}
