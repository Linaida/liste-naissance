<?php

namespace App\Entity;

use App\Repository\StoreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StoreRepository::class)]
class Store
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $searchUrlPattern = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $regex = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $active = true;

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

    public function getSearchUrlPattern(): ?string
    {
        return $this->searchUrlPattern;
    }

    public function setSearchUrlPattern(string $searchUrlPattern): static
    {
        $this->searchUrlPattern = $searchUrlPattern;

        return $this;
    }

    public function getRegex(): ?string
    {
        return $this->regex;
    }

    public function setRegex(?string $regex): static
    {
        $this->regex = $regex;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
