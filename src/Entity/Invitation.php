<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvitationRepository::class)]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\ManyToOne(targetEntity: Socialnet::class, inversedBy: 'invitations')]
    #[ORM\JoinColumn(nullable: false)]
    private $socialnet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSocialnet(): ?Socialnet
    {
        return $this->socialnet;
    }

    public function setSocialnet(?Socialnet $socialnet): self
    {
        $this->socialnet = $socialnet;

        return $this;
    }
}
