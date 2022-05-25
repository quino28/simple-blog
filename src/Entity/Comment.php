<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $article_id;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private $body;

    #[ORM\Column(type: 'boolean')]
    private $is_hide;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleId(): ?int
    {
        return $this->article_id;
    }

    public function setArticleId(int $article_id): self
    {
        $this->article_id = $article_id;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function isIsHide(): ?bool
    {
        return $this->is_hide;
    }

    public function setIsHide(bool $is_hide): self
    {
        $this->is_hide = $is_hide;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->created_at = new DateTime();
    }
}
