<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $icon = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(length: 255)]
    private ?string $imagepath = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $salesPitch = null;

    #[ORM\Column(length: 255)]
    private ?string $quote = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getImagepath(): ?string
    {
        return $this->imagepath;
    }

    public function setImagepath(string $imagepath): static
    {
        $this->imagepath = $imagepath;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSalesPitch(): ?string
    {
        return $this->salesPitch;
    }

    public function setSalesPitch(string $salesPitch): static
    {
        $this->salesPitch = $salesPitch;

        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(string $quote): static
    {
        $this->quote = $quote;

        return $this;
    }
}
