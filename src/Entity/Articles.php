<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $title = null;

    #[ORM\Column(length: 5000)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $release_date = null;

    #[ORM\Column(length: 200)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'mentioned_in')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'consults')]
    private Collection $read_by;

    #[ORM\ManyToMany(targetEntity: Categories::class, inversedBy: 'articles'), ORM\JoinTable(name: 'includes')]
    private Collection $includes;

    #[ORM\ManyToMany(targetEntity: Tags::class, inversedBy: 'articles'), ORM\JoinTable(name: 'concerns')]
    private Collection $concerns;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Opinions::class, orphanRemoval: true)]
    private Collection $refers_to;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->read_by = new ArrayCollection();
        $this->includes = new ArrayCollection();
        $this->concerns = new ArrayCollection();
        $this->refers_to = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->release_date;
    }

    public function setReleaseDate(\DateTimeInterface $release_date): self
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $user->addMentionedIn($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeMentionedIn($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getReadBy(): Collection
    {
        return $this->read_by;
    }

    public function addReadBy(Users $readBy): self
    {
        if (!$this->read_by->contains($readBy)) {
            $this->read_by->add($readBy);
            $readBy->addConsult($this);
        }

        return $this;
    }

    public function removeReadBy(Users $readBy): self
    {
        if ($this->read_by->removeElement($readBy)) {
            $readBy->removeConsult($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getIncludes(): Collection
    {
        return $this->includes;
    }

    public function addInclude(Categories $include): self
    {
        if (!$this->includes->contains($include)) {
            $this->includes->add($include);
        }

        return $this;
    }

    public function removeInclude(Categories $include): self
    {
        $this->includes->removeElement($include);

        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getConcerns(): Collection
    {
        return $this->concerns;
    }

    public function addConcern(Tags $concern): self
    {
        if (!$this->concerns->contains($concern)) {
            $this->concerns->add($concern);
        }

        return $this;
    }

    public function removeConcern(Tags $concern): self
    {
        $this->concerns->removeElement($concern);

        return $this;
    }

    /**
     * @return Collection<int, Opinions>
     */
    public function getRefersTo(): Collection
    {
        return $this->refers_to;
    }

    public function addRefersTo(Opinions $refersTo): self
    {
        if (!$this->refers_to->contains($refersTo)) {
            $this->refers_to->add($refersTo);
            $refersTo->setArticle($this);
        }

        return $this;
    }

    public function removeRefersTo(Opinions $refersTo): self
    {
        if ($this->refers_to->removeElement($refersTo)) {
            // set the owning side to null (unless already changed)
            if ($refersTo->getArticle() === $this) {
                $refersTo->setArticle(null);
            }
        }

        return $this;
    }
}
