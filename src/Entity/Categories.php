<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $img_path = null;

    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'subscriptions')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Articles::class, mappedBy: 'includes')]
    private Collection $articles;

    #[ORM\ManyToOne(inversedBy: 'supervises')]
    private ?Categories $sub = null;

    #[ORM\OneToMany(mappedBy: 'sub', targetEntity: Categories::class)]
    private Collection $supervises;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->supervises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->img_path;
    }

    public function setImgPath(string $img_path): self
    {
        $this->img_path = $img_path;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addSuscribed($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeSuscribed($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addIncludes($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeIncludes($this);
        }

        return $this;
    }

    public function getSub(): ?Categories
    {
        return $this->sub;
    }

    public function setSub(?Categories $sub): self
    {
        $this->sub = $sub;

        return $this;
    }

        /**
     * @return Collection<int, Categories>
     */
    public function getSupervises(): Collection
    {
        return $this->supervises;
    }

    public function addSupervise(Categories $supervise): self
    {
        if (!$this->supervises->contains($supervise)) {
            $this->supervises->add($supervise);
            $supervise->setSub($this);
        }

        return $this;
    }

    public function removeSupervise(Categories $supervise): self
    {
        if ($this->supervises->removeElement($supervise)) {
            // set the owning side to null (unless already changed)
            if ($supervise->getSub() === $this) {
                $supervise->setSub(null);
            }
        }

        return $this;
    }
}
