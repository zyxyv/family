<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $media_photo;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private $album;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private $owner_photo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaPhoto(): ?string
    {
        return $this->media_photo;
    }

    public function setMediaPhoto(string $media_photo): self
    {
        $this->media_photo = $media_photo;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getOwnerPhoto(): ?User
    {
        return $this->owner_photo;
    }

    public function setOwnerPhoto(?User $owner_photo): self
    {
        $this->owner_photo = $owner_photo;

        return $this;
    }
}
