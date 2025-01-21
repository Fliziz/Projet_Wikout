<?php

namespace App\Entity;

use App\Repository\FicheContenuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheContenuRepository::class)]
class FicheContenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Image1 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Contenu1 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Contenu2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Image2 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Contenu3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Image3 = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Etude = null;

    #[ORM\OneToOne(cascade : ['remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fiches $Fiche = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage1(): ?string
    {
        return $this->Image1;
    }

    public function setImage1(?string $Image1): static
    {
        $this->Image1 = $Image1;

        return $this;
    }

    public function getContenu1(): ?string
    {
        return $this->Contenu1;
    }

    public function setContenu1(?string $Contenu1): static
    {
        $this->Contenu1 = $Contenu1;

        return $this;
    }

    public function getContenu2(): ?string
    {
        return $this->Contenu2;
    }

    public function setContenu2(?string $Contenu2): static
    {
        $this->Contenu2 = $Contenu2;

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->Image2;
    }

    public function setImage2(?string $Image2): static
    {
        $this->Image2 = $Image2;

        return $this;
    }

    public function getContenu3(): ?string
    {
        return $this->Contenu3;
    }

    public function setContenu3(?string $Contenu3): static
    {
        $this->Contenu3 = $Contenu3;

        return $this;
    }

    public function getImage3(): ?string
    {
        return $this->Image3;
    }

    public function setImage3(?string $Image3): static
    {
        $this->Image3 = $Image3;

        return $this;
    }

    public function getEtude(): ?string
    {
        return $this->Etude;
    }

    public function setEtude(string $Etude): static
    {
        $this->Etude = $Etude;

        return $this;
    }

    public function getFiche(): ?fiches
    {
        return $this->Fiche;
    }

    public function setFiche(fiches $Fiche): static
    {
        $this->Fiche = $Fiche;

        return $this;
    }

}
