<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title_en = null;

    #[ORM\Column(length: 255)]
    private ?string $title_fr = null;

    #[ORM\Column(length: 100)]
    private ?string $author = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetimePosted = null;

    #[ORM\Column(length: 100)]
    private ?string $breadcrumb_en = null;

    #[ORM\Column(length: 100)]
    private ?string $breadcrumb_fr = null;

    #[ORM\Column(length: 100)]
    private ?string $imagePath = null;

    #[ORM\OneToMany(targetEntity: Paragraph::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $paragraphs;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostCategory $category = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostType $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $authorImagepath = null;

    #[ORM\Column]
    private ?int $views = null;

    #[ORM\Column]
    private ?int $commentCount = null;

    #[ORM\OneToOne(mappedBy: 'post', cascade: ['persist', 'remove'])]
    private ?Quote $quote = null;

    public function __construct()
    {
        $this->paragraphs = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(string $language = "en"): ?string
    {
        if($language == "en")
        {
            return $this->title_en;
        }
        else
        {
            return $this->title_fr;
        }
    }

    public function setTitle(string $title, string $language = "en"): static
    {
        if($language == "en")
        {
            $this->title_en = $title;
        }
        else
        {
            $this->title_fr = $title;
        }

        return $this;
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

    public function getDatetimePosted(string $language = "en"): ?\DateTimeInterface
    {
        return $this->datetimePosted;
    }

    public function getTimePosted(): ?string
    {
        return $this->datetimePosted->format('M, j Y \a\t g:i a');
    }

    public function setDatetimePosted(\DateTimeInterface $datetimePosted): static
    {
        $this->datetimePosted = $datetimePosted;

        return $this;
    }

    public function getBreadcrumb(string $language = "en"): ?string
    {
        if($language == "en")
        {
            return $this->breadcrumb_en;
        }
        else
        {
            return $this->breadcrumb_fr;
        }
    }

    public function setBreadcrumb(string $breadcrumb, string $language = "en"): static
    {
        if($language == "en")
        {
            $this->breadcrumb_en = $breadcrumb;
        }
        else
        {
            $this->breadcrumb_fr = $breadcrumb;
        }

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * @return Collection<int, Paragraph>
     */
    public function getParagraphs(string $language = "en"): Collection
    {
        $filter = null;
        $filter = $this->paragraphs->filter(function($paragraph) use ($language){
            return $paragraph->getLanguage() == $language;
        });
        return $filter;
    }

    public function addParagraph(Paragraph $paragraph, string $language = "en"): static
    {
        if (!$this->paragraphs->contains($paragraph)) {
            $this->paragraphs->add($paragraph);
            $paragraph->setPost($this);
        }

        return $this;
    }

    public function removeParagraph(Paragraph $paragraph): static
    {
        if ($this->paragraphs->removeElement($paragraph)) {
            // set the owning side to null (unless already changed)
            if ($paragraph->getPost() === $this) {
                $paragraph->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getCategory(): PostCategory
    {
        return $this->category;
    }

    public function setCategory(PostCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getType(): PostType
    {
        return $this->type;
    }

    public function setType(PostType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAuthorImagepath(): ?string
    {
        return $this->authorImagepath;
    }

    public function setAuthorImagepath(?string $authorImagepath): static
    {
        $this->authorImagepath = $authorImagepath;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): static
    {
        $this->views = $views;

        return $this;
    }

    public function getCommentCount(): ?int
    {
        return $this->commentCount;
    }

    public function setCommentCount(int $commentCount): static
    {
        $this->commentCount = $commentCount;

        return $this;
    }

    public function getQuote(): ?Quote
    {
        return $this->quote;
    }

    public function setQuote(?Quote $quote): static
    {
        // unset the owning side of the relation if necessary
        if ($quote === null && $this->quote !== null) {
            $this->quote->setPost(null);
        }

        // set the owning side of the relation if necessary
        if ($quote !== null && $quote->getPost() !== $this) {
            $quote->setPost($this);
        }

        $this->quote = $quote;

        return $this;
    }
}
