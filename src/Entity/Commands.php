<?php
namespace App\Entity;

use App\Repository\CommandsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandsRepository::class)]
class Commands
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $command_etat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $command_date = null;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $total = null;

    #[ORM\OneToMany(targetEntity: Detail::class, mappedBy: 'commande', cascade: ['remove'], orphanRemoval: true)]
    private Collection $details;


    #[ORM\Column(length: 20)]
    private ?string $payment_method = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $delivery_addresse = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Regex(
        pattern: '/^[0-9]{5}$/',
        message: 'Veuillez saisir un code postal valide (exactement 5 chiffres).'
    )]
    private ?string $cp = null;

    public function __construct()
    {
        $this->details = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommandEtat(): ?string
    {
        return $this->command_etat;
    }

    public function setCommandEtat(string $command_etat): static
    {
        $this->command_etat = $command_etat;

        return $this;
    }

    public function getCommandDate(): ?\DateTimeInterface
    {
        return $this->command_date;
    }

    public function setCommandDate(\DateTimeInterface $command_date): static
    {
        $this->command_date = $command_date;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(Detail $detail): static
    {
        if (!$this->details->contains($detail)) {
            $this->details->add($detail);
            $detail->setCommande($this);
        }

        return $this;
    }

    public function removeDetail(Detail $detail): static
    {
        if ($this->details->removeElement($detail)) {
            if ($detail->getCommande() === $this) {
                $detail->setCommande(null);
            }
        }

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): static
    {
        $this->payment_method = $payment_method;

        return $this;
    } 

    public function getDeliveryAddresse(): ?string
    {
        return $this->delivery_addresse;
    }

    public function setDeliveryAddresse(?string $delivery_addresse): static
    {
        $this->delivery_addresse = $delivery_addresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }
}
