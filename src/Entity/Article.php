<?php

namespace App\Entity;

use \DateTimeImmutable;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(name: "created_at")]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: "updated_at")]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: "boolean", options: ["default" => "false"])]
    private bool $booked = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleLink::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $links;

    public function __construct()
{
    $this->links = new ArrayCollection();
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(ArticleLink $link): static
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->setArticle($this);
        }

        return $this;
    }

    public function removeLink(ArticleLink $link): static
    {
        if ($this->links->removeElement($link)) {
            if ($link->getArticle() === $this) {
                $link->setArticle(null);
            }
        }
        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $now = new DateTimeImmutable();

        if ($this->createdAt === null) {
            $this->createdAt = $now;
        }

        $this->updatedAt = $now;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isBooked(): bool
    {
        return $this->booked;
    }

    public function setBooked(bool $booked): static
    {
        $this->booked = $booked;

        return $this;
    }
}
