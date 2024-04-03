<?php

namespace App\Entity;

use App\Repository\TestimonialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestimonialRepository::class)]
class Testimonial
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(length: 255)]
    private ?string $imagepath = null;

    #[ORM\OneToMany(targetEntity: TestimonialLang::class, mappedBy: 'testimonial', orphanRemoval: true)]
    private Collection $testimonialLangs;

    public function __construct()
    {
        $this->testimonialLangs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

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

    public function getImagepath(): ?string
    {
        return $this->imagepath;
    }

    public function setImagepath(string $imagepath): static
    {
        $this->imagepath = $imagepath;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection<int, TestimonialLang>
     */
    public function getTestimonialLangs(): Collection
    {
        return $this->testimonialLangs;
    }

    public function addTestimonialLang(TestimonialLang $testimonialLang): static
    {
        if (!$this->testimonialLangs->contains($testimonialLang)) {
            $this->testimonialLangs->add($testimonialLang);
            $testimonialLang->setTestimonial($this);
        }

        return $this;
    }

    public function removeTestimonialLang(TestimonialLang $testimonialLang): static
    {
        if ($this->testimonialLangs->removeElement($testimonialLang)) {
            // set the owning side to null (unless already changed)
            if ($testimonialLang->getTestimonial() === $this) {
                $testimonialLang->setTestimonial(null);
            }
        }

        return $this;
    }
}
