<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SocieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocieteRepository::class)]
#[ApiResource]
class Societe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 14)]
    private ?string $NumeroSiret = null;

    #[ORM\Column(length: 255)]
    private ?string $Adresse = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'societes')]
    private Collection $utilisateurs;

    /**
     * @var Collection<int, Projet>
     */
    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: 'societe')]
    private Collection $Projet;

    /**
     * @var Collection<int, Projet>
     */
    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: 'Societe')]
    private Collection $projets;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->Projet = new ArrayCollection();
        $this->projets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNumeroSiret(): ?string
    {
        return $this->NumeroSiret;
    }

    public function setNumeroSiret(string $NumeroSiret): static
    {
        $this->NumeroSiret = $NumeroSiret;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): static
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(User $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
        }

        return $this;
    }

    public function removeUtilisateur(User $utilisateur): static
    {
        $this->utilisateurs->removeElement($utilisateur);

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getProjet(): Collection
    {
        return $this->Projet;
    }

    public function addProjet(Projet $projet): static
    {
        if (!$this->Projet->contains($projet)) {
            $this->Projet->add($projet);
            $projet->setSociete($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): static
    {
        if ($this->Projet->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getSociete() === $this) {
                $projet->setSociete(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }
}
