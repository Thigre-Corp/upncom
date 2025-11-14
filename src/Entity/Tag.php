<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[UniqueEntity('tagName')]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $tagName = null;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\ManyToMany(targetEntity: Service::class, mappedBy: 'tags')]
    private Collection $services;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'tags')]
    private Collection $articles;

    /**
     * @var Collection<int, Realisation>
     */
    #[ORM\ManyToMany(targetEntity: Realisation::class, mappedBy: 'tags', cascade: ['persist'])]
    private Collection $realisations;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->realisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagName(): ?string
    {
        return $this->tagName;
    }

    public function setTagName(string $tagName): static
    {
        $this->tagName = $tagName;

        return $this;
    }

    public function __toString()
    {
        return $this->tagName;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->addTag($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            $service->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getarticles(): Collection
    {
        return $this->articles;
    }

    public function addarticles(Article $articles): static
    {
        if (!$this->articles->contains($articles)) {
            $this->articles->add($articles);
            $articles->addTag($this);
        }

        return $this;
    }

    public function removearticles(Article $articles): static
    {
        if ($this->articles->removeElement($articles)) {
            $articles->removeTag($this);
        }

        return $this;
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
            $realisation->addTag($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): static
    {
        if ($this->realisations->removeElement($realisation)) {
            $realisation->removeTag($this);
        }

        return $this;
    }
}
