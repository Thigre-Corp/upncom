<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column]
    private ?\DateTime $dateModification = null;

    #[ORM\Column]
    private ?bool $estPublie = false;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $auteur = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(onDelete:"SET NULL", nullable:true)]
    private ?Image $imagePrincipale = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete:"SET NULL", nullable:true)]
    private ?Image $imageDeux = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete:"SET NULL", nullable:true)]
    private ?Image $imageTrois = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete:"SET NULL", nullable:true)]
    private ?Image $imageQuatre = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'articles')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModification(): ?\DateTime
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTime $dateModification): static
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function isEstPublie(): ?bool
    {
        return $this->estPublie;
    }

    public function setEstPublie(bool $estPublie): static
    {
        $this->estPublie = $estPublie;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getImagePrincipale(): ?Image
    {
        return $this->imagePrincipale;
    }

    public function setImagePrincipale(?Image $imagePrincipale): static
    {
        $this->imagePrincipale = $imagePrincipale;

        return $this;
    }

    public function getImageDeux(): ?Image
    {
        return $this->imageDeux;
    }

    public function setImageDeux(?Image $imageDeux): static
    {
        $this->imageDeux = $imageDeux;

        return $this;
    }

    public function getImageTrois(): ?Image
    {
        return $this->imageTrois;
    }

    public function setImageTrois(?Image $imageTrois): static
    {
        $this->imageTrois = $imageTrois;

        return $this;
    }

    public function getImageQuatre(): ?Image
    {
        return $this->imageQuatre;
    }

    public function setImageQuatre(?Image $imageQuatre): static
    {
        $this->imageQuatre = $imageQuatre;

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
}
