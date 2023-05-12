<?php

namespace App\Entity;

use App\Repository\OpinionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpinionsRepository::class)]
class Opinions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $opinion_value = null;

    #[ORM\ManyToOne(inversedBy: 'rated')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'refers_to')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Articles $article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpinionValue(): ?float
    {
        return $this->opinion_value;
    }

    public function setOpinionValue(float $opinion_value): self
    {
        $this->opinion_value = $opinion_value;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArticle(): ?Articles
    {
        return $this->article;
    }

    public function setArticle(?Articles $article): self
    {
        $this->article = $article;

        return $this;
    }
}
