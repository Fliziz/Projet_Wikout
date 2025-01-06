<?php

namespace App\Entity;

use App\Repository\FichesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FichesRepository::class)]
class Fiches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    #[ORM\Column(type: 'text')]
    private ?string $Description = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateurs $Utilisateur = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false )]
    private ?Categories $Categorie = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $Type = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Difficulte $Difficulte = null;

    /**
     * @var Collection<int, commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'Fiche', orphanRemoval: true)]
    private Collection $Commentaires;

    #[ORM\Column(length: 255)]
    private ?string $Photo = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $Editer = null;

    public function __construct()
    {
        $this->Commentaire = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, commentaire>
     */
    public function getCommentaire(): Collection
    {
        return $this->Commentaire;
    }

    public function addCommentaire(commentaires $commentaire): static
    {
        if (!$this->Commentaire->contains($commentaire)) {
            $this->Commentaire->add($commentaire);
            $commentaire->setFiche($this);
        }

        return $this;
    }

    public function removeCommentaire(commentaires $commentaire): static
    {
        if ($this->Commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFiche() === $this) {
                $commentaire->setFiche(null);
            }
        }

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
