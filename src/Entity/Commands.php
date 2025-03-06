<?php

namespace App\Entity;

use App\Repository\CommandsRepository;
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

    #[ORM\ManyToOne(inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

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
}
