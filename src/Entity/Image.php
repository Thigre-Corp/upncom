<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mediaURL = null;

    private ?File $imageFile = null; // non persisté, pour gestion upload via form

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $altText = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $maskSVG = null;

    #[ORM\ManyToOne(inversedBy: 'images', cascade:['persist'])]
    private ?Article $article = null;

    /**
     * @var Collection<int, Realisation>
     */
    #[ORM\OneToMany(targetEntity: Realisation::class, mappedBy: 'imageCouverture')]
    private Collection $realistationCouverture;

    /**
     * @var Collection<int, Realisation>
     */
    #[ORM\ManyToMany(targetEntity: Realisation::class, mappedBy: 'images')]
    private Collection $realisations;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'imagePrincipale')]
    private Collection $articles;

    public function __construct()
    {
        $this->realistationCouverture = new ArrayCollection();
        $this->realisations = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaURL(): ?string
    {
        return $this->mediaURL;
    }

    public function setMediaURL(string $mediaURL): static
    {
        $this->mediaURL = $mediaURL;

        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(?string $altText): static
    {
        $this->altText = $altText;

        return $this;
    }

    public function getMaskSVG(): ?string
    {
        return $this->maskSVG;
    }

    public function setMaskSVG(?string $maskSVG): static
    {
        $this->maskSVG = $maskSVG;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;
        return $this;
    }

    public function __toString()
    {
        return $this->mediaURL ?? 'null';
    }

    /**
     * @return Collection<int, Realisation>
     */
    public function getRealistationCouverture(): Collection
    {
        return $this->realistationCouverture;
    }

    public function addRealistationCouverture(Realisation $realistationCouverture): static
    {
        if (!$this->realistationCouverture->contains($realistationCouverture)) {
            $this->realistationCouverture->add($realistationCouverture);
            $realistationCouverture->setImageCouverture($this);
        }

        return $this;
    }

    public function removeRealistationCouverture(Realisation $realistationCouverture): static
    {
        if ($this->realistationCouverture->removeElement($realistationCouverture)) {
            // set the owning side to null (unless already changed)
            if ($realistationCouverture->getImageCouverture() === $this) {
                $realistationCouverture->setImageCouverture(null);
            }
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
            $realisation->addImage($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): static
    {
        if ($this->realisations->removeElement($realisation)) {
            $realisation->removeImage($this);
        }

        return $this;
    }

    /**
     * Get the value of imageFile
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setImagePrincipale($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getImagePrincipale() === $this) {
                $article->setImagePrincipale(null);
            }
        }

        return $this;
    }
}
