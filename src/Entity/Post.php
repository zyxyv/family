<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private $post_user;

    #[ORM\ManyToOne(targetEntity: Socialnet::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private $socialnet;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $parent;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $media;

    #[ORM\Column(type: 'string', length: 15)]
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostUser(): ?User
    {
        return $this->post_user;
    }

    public function setPostUser(?User $post_user): self
    {
        $this->post_user = $post_user;

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

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(?int $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }
}
