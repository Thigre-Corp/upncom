<?php

namespace App\Entity;

use App\Repository\RealisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealisationRepository::class)]
class Realisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $accroche = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $resumeMission = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'realistationCouverture')]
    #[ORM\JoinColumn(onDelete:"SET NULL", nullable:true)]
    private ?Image $imageCouverture = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\ManyToMany(targetEntity: Image::class, inversedBy: 'realisations')]
    private Collection $images;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'realisations')]
    private Collection $tags;

    #[ORM\ManyToOne(inversedBy: 'realisations')]
    private ?Client $clients = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccroche(): ?string
    {
        return $this->accroche;
    }

    public function setAccroche(string $accroche): static
    {
        $this->accroche = $accroche;

        return $this;
    }

    public function getResumeMission(): ?string
    {
        return $this->resumeMission;
    }

    public function setResumeMission(string $resumeMission): static
    {
        $this->resumeMission = $resumeMission;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImageCouverture(): ?Image
    {
        return $this->imageCouverture;
    }

    public function setImageCouverture(?Image $imageCouverture): static
    {
        $this->imageCouverture = $imageCouverture;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        $this->images->removeElement($image);

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function __toString()
    {
        return $this->getAccroche();
    }

    public function getClients(): ?Client
    {
        return $this->clients;
    }

    public function setClients(?Client $clients): static
    {
        $this->clients = $clients;

        return $this;
    }
}
