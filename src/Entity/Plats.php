<?php

namespace App\Entity;

use App\Repository\PlatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\Column]
    private ?int $plat_prix = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $plat_photo = null;

    #[ORM\Column(length: 50)]
    private ?string $active = null;

    #[ORM\ManyToOne(inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $categorie = null;

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

    public function getPlatPrix(): ?int
    {
        return $this->plat_prix;
    }

    public function setPlatPrix(int $plat_prix): static
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

    public function getActive(): ?string
    {
        return $this->active;
    }

    public function setActive(string $active): static
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
}
