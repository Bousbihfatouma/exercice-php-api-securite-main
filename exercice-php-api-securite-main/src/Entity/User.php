<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Societe::class, mappedBy: 'utilisateurs')]
    private Collection $societes;

    #[ORM\ManyToMany(targetEntity: Projet::class, inversedBy: 'users')]
    private Collection $projets;

    #[ORM\OneToMany(targetEntity: UserSocieteRole::class, mappedBy: 'user')]
    private Collection $userSocieteRoles; // Nouvelle propriété pour les rôles

    public function __construct()
    {
        $this->societes = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->userSocieteRoles = new ArrayCollection(); // Initialisation de la collection des rôles
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; // Ajouter le rôle utilisateur par défaut
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Optionnel : pour effacer les données sensibles
    }

    public function getSocietes(): Collection
    {
        return $this->societes;
    }

    public function addSociete(Societe $societe): static
    {
        if (!$this->societes->contains($societe)) {
            $this->societes->add($societe);
            $societe->addUtilisateur($this);
        }
        return $this;
    }

    public function removeSociete(Societe $societe): static
    {
        if ($this->societes->removeElement($societe)) {
            $societe->removeUtilisateur($this);
        }
        return $this;
    }

    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): static
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
        }
        return $this;
    }

    public function removeProjet(Projet $projet): static
    {
        $this->projets->removeElement($projet);
        return $this;
    }

    public function getUserSocieteRoles(): Collection
    {
        return $this->userSocieteRoles; // Getter pour les rôles
    }

    public function addUserSocieteRole(UserSocieteRole $userSocieteRole): static
    {
        if (!$this->userSocieteRoles->contains($userSocieteRole)) {
            $this->userSocieteRoles->add($userSocieteRole);
            $userSocieteRole->setUser($this); // Lier le rôle à cet utilisateur
        }
        return $this;
    }

    public function removeUserSocieteRole(UserSocieteRole $userSocieteRole): static
    {
        if ($this->userSocieteRoles->removeElement($userSocieteRole)) {
            // Dissocier l'utilisateur du rôle s'il est supprimé
            if ($userSocieteRole->getUser() === $this) {
                $userSocieteRole->setUser(null);
            }
        }
        return $this;
    }
}
