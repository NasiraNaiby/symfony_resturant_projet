<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]

class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $cat_nom = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT)]
    private ?string $cat_description = null;

    /**
     * @var Collection<int, Plats>
     */
    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Plats::class)]
    private Collection $plats;
    

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cat_image = null;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatNom(): ?string
    {
        return $this->cat_nom;
    }

    public function setCatNom(string $cat_nom): static
    {
        $this->cat_nom = $cat_nom;

        return $this;
    }

    public function getCatDescription(): ?string
    {
        return $this->cat_description;
    }

    public function setCatDescription(string $cat_description): static
    {
        $this->cat_description = $cat_description;

        return $this;
    }

    /**
     * @return Collection<int, Plats>
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plats $plat): static
    {
        if (!$this->plats->contains($plat)) {
            $this->plats->add($plat);
            $plat->setCategorie($this);
        }

        return $this;
    }

    public function removePlat(Plats $plat): static
    {
        if ($this->plats->removeElement($plat)) {
            if ($plat->getCategorie() === $this) {
                $plat->setCategorie(null);
            }
        }

        return $this;
    }
    // Add the __toString method here
    public function __toString(): string
    {
        return $this->cat_nom; // This ensures that the category name is displayed as a string
    }

    public function getCatImage(): ?string
    {
        return $this->cat_image;
    }

    public function setCatImage(?string $cat_image): static
    {
        $this->cat_image = $cat_image;

        return $this;
    }
}
