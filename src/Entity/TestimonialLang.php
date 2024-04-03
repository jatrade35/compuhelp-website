<?php

namespace App\Entity;

use App\Repository\TestimonialLangRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestimonialLangRepository::class)]
class TestimonialLang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2)]
    private ?string $language = null;

    #[ORM\Column(length: 255)]
    private ?string $position = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'testimonialLangs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Testimonial $testimonial = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getTestimonial(): ?Testimonial
    {
        return $this->testimonial;
    }

    public function setTestimonial(?Testimonial $testimonial): static
    {
        $this->testimonial = $testimonial;

        return $this;
    }
}
