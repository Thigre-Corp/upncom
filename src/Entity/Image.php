<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imageOrg = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageMod = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $altText = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $maskSVG = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageOrg(): ?string
    {
        return $this->imageOrg;
    }

    public function setImageOrg(string $imageOrg): static
    {
        $this->imageOrg = $imageOrg;

        return $this;
    }

    public function getImageMod(): ?string
    {
        return $this->imageMod;
    }

    public function setImageMod(?string $imageMod): static
    {
        $this->imageMod = $imageMod;

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
}
