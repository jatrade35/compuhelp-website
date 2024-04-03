<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, options:["default"=>""])]
    private ?string $author = null;

    #[ORM\Column(length: 255, options:["default"=>""])]
    private ?string $avatar = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'replies')]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $comment = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'comment', orphanRemoval: true)]
    private Collection $replies;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Post $post = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options:["default"=>"CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $datetimeposted = null;

    #[ORM\OneToMany(targetEntity: CommentLang::class, mappedBy: 'comment', orphanRemoval: true)]
    private Collection $commentLangs;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
        $this->commentLangs = new ArrayCollection();
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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

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

    public function getComment(): ?self
    {
        return $this->comment;
    }

    public function setComment(?self $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(self $reply): static
    {
        if (!$this->replies->contains($reply)) {
            $this->replies->add($reply);
            $reply->setComment($this);
        }

        return $this;
    }

    public function removeReply(self $reply): static
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getComment() === $this) {
                $reply->setComment(null);
            }
        }

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getDatetimeposted(): ?\DateTimeInterface
    {
        return $this->datetimeposted;
    }

    public function setDatetimeposted(\DateTimeInterface $datetimeposted): static
    {
        $this->datetimeposted = $datetimeposted;

        return $this;
    }

    public function getAge(): string
    {
        $datetimePosted = new Datetime($this->datetimeposted->format('j M Y g:i a'));
        $diff = $datetimePosted->diff(new Datetime());
        if( $diff->y > 0)
        {
            $plural = ($diff->y > 1) ? "s": ""; 
            $age = "$diff->y year" . $plural . " old.";
        }
        elseif($diff->m > 0)
        {
            $plural = ($diff->m > 1) ? "s": ""; 
            $age = "$diff->m month" . $plural . " old.";
        }
        elseif($diff->d > 0)
        {
            $plural = ($diff->d > 1) ? "s": ""; 
            $age = "$diff->d day" . $plural . " old.";
        }
        elseif($diff->h > 0)
        {
            $plural = ($diff->h > 1) ? "s": ""; 
            $age = "$diff->h hour" . $plural . " old.";
        }
        elseif($diff->i > 0)
        {
            $plural = ($diff->i > 1) ? "s": ""; 
            $age = "$diff->i minute" . $plural . " old.";
        }
        else
        {
            $plural = ($diff->s > 1) ? "s": ""; 
            $age = "$diff->s second" . $plural . " old.";
        }

        return $age;
    }

    /**
     * @return Collection<int, CommentLang>
     */
    public function getCommentLangs(): Collection
    {
        return $this->commentLangs;
    }

    public function addCommentLang(CommentLang $commentLang): static
    {
        if (!$this->commentLangs->contains($commentLang)) {
            $this->commentLangs->add($commentLang);
            $commentLang->setComment($this);
        }

        return $this;
    }

    public function removeCommentLang(CommentLang $commentLang): static
    {
        if ($this->commentLangs->removeElement($commentLang)) {
            // set the owning side to null (unless already changed)
            if ($commentLang->getComment() === $this) {
                $commentLang->setComment(null);
            }
        }

        return $this;
    }
}
