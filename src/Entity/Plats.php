<?php

namespace App\Entity;

use App\Repository\PlatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: PlatsRepository::class)]

class Plats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $plat_nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $plat_description = null;

    #[ORM\Column(type: "float")] 
    private ?float $plat_prix = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $plat_photo = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])] 
    private ?bool $active = false; // Default value is inactive

    #[ORM\ManyToOne(inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $categorie = null;

    /**
     * @var Collection<int, Detail>
     */
    #[ORM\OneToMany(targetEntity: Detail::class, mappedBy: 'plat')]
    private Collection $details;

    public function __construct()
    {
        $this->details = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlatNom(): ?string
    {
        return $this->plat_nom;
    }

    public function setPlatNom(string $plat_nom): static
    {
        $this->plat_nom = $plat_nom;

        return $this;
    }

    public function getPlatDescription(): ?string
    {
        return $this->plat_description;
    }

    public function setPlatDescription(string $plat_description): static
    {
        $this->plat_description = $plat_description;

        return $this;
    }

    
    public function getPlatPrix(): ?float
    {
        return $this->plat_prix;
    }

    public function setPlatPrix(float $plat_prix): static
    {
        $this->plat_prix = $plat_prix;

        return $this;
    }

    public function getPlatPhoto(): ?string
    {
        return $this->plat_photo;
    }

    public function setPlatPhoto(string $plat_photo): static
    {
        $this->plat_photo = $plat_photo;

        return $this;
    }

    public function getActive(): ?bool 
    {
        return $this->active;
    }

    public function setActive(bool $active): static 
    {
        $this->active = $active;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Detail>
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(Detail $detail): static
    {
        if (!$this->details->contains($detail)) {
            $this->details->add($detail);
            $detail->setPlat($this);
        }

        return $this;
    }

    public function removeDetail(Detail $detail): static
    {
        if ($this->details->removeElement($detail)) {
            
            if ($detail->getPlat() === $this) {
                $detail->setPlat(null);
            }
        }

        return $this;
    }
}
