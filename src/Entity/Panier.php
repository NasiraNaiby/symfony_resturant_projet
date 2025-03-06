<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?int $plat_prix = null;

    #[ORM\Column]
    private ?int $totale_prix = null;

    /**
     * @var Collection<int, Plats>
     */
    #[ORM\ManyToMany(targetEntity: Plats::class)]
    private Collection $plats;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Commands $commands = null;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

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

    public function getTotalePrix(): ?int
    {
        return $this->totale_prix;
    }

    public function setTotalePrix(int $totale_prix): static
    {
        $this->totale_prix = $totale_prix;

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
        }

        return $this;
    }

    public function removePlat(Plats $plat): static
    {
        $this->plats->removeElement($plat);

        return $this;
    }

    public function getCommands(): ?Commands
    {
        return $this->commands;
    }

    public function setCommands(?Commands $commands): static
    {
        $this->commands = $commands;

        return $this;
    }
}
