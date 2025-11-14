<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clientURL = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete:"SET NULL", nullable:true)]
    private ?Image $imageClient = null;

    /**
     * @var Collection<int, Realisation>
     */
    #[ORM\OneToMany(targetEntity: Realisation::class, mappedBy: 'clients', cascade:['persist'])]
    private Collection $realisations;

    public function __construct()
    {
        $this->realisations = new ArrayCollection();
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

    public function getClientURL(): ?string
    {
        return $this->clientURL;
    }

    public function setClientURL(?string $clientURL): static
    {
        $this->clientURL = $clientURL;

        return $this;
    }

    public function getImageClient(): ?Image
    {
        return $this->imageClient;
    }

    public function setImageClient(?Image $imageClient): static
    {
        $this->imageClient = $imageClient;
        return $this;
    }

    public function __toString()
    {
        return $this->getNom();
    }

    /**
     * @return Collection<int, Realisation>
     */
    public function getRealisations(): Collection
    {
        return $this->realisations;
    }

    public function addRealisation(Realisation $realisation): static
    {
        if (!$this->realisations->contains($realisation)) {
            $this->realisations->add($realisation);
            $realisation->setClients($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): static
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getClients() === $this) {
                $realisation->setClients(null);
            }
        }

        return $this;
    }
}
