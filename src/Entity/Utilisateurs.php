<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
class Utilisateurs implements UserInterface, PasswordAuthenticatedUserInterface // La classe User implémente UserInterface et PasswordAuthenticatedUserInterface, ce qui signifie que cette classe doit gérer les informations d'authentification de l'utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Photo_Profil = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Le pseudo est obligatoire.")]
    #[Assert\Length(min: 3, max: 20, minMessage: "Le pseudo doit contenir au moins 3 caractères.", maxMessage: "Le pseudo ne doit pas dépasser 20 caractères.")]
    private ?string $Pseudo = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $Email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
    #[Assert\Length(
        min: 12, 
        minMessage: "Le mot de passe doit contenir au moins 12 caractères."
    )]
    #[Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{12,}$/",
        message: "Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial."
    )]
    private ?string $Mot_de_Passe = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $Nom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $Prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Age = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $Genre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Description = null;

    #[ORM\Column]
    private array $Role = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhotoProfil(): ?string
    {
        return $this->Photo_Profil;
    }

    public function setPhotoProfil(?string $Photo_Profil): static
    {
        $this->Photo_Profil = $Photo_Profil;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->Pseudo;
    }

    public function setPseudo(string $Pseudo): static
    {
        $this->Pseudo = $Pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Mot_de_Passe;
    }

    public function setPassword(string $Mot_de_Passe): static
    {
        $this->Mot_de_Passe = $Mot_de_Passe;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(?string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(?string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getAge(): ?\DateTimeInterface
    {
        return $this->Age;
    }

    public function setAge(?\DateTimeInterface $Age): static
    {
        $this->Age = $Age;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->Genre;
    }

    public function setGenre(?string $Genre): static
    {
        $this->Genre = $Genre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->Role;
    }

    public function setRoles(array $Role): static
    {
        $this->Role = $Role;

        return $this;
    }

    public function eraseCredentials():void
    {
        //nettoyage des données sensibles en mémoire
    }

    public function getUserIdentifier(): string
    {
        return $this->Email; // le champ unique pour identifier l'utilisateur
    }
}
