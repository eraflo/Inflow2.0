<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotNull]
    private ?string $title = null;

    #[ORM\Column(length: 5000)]
    #[Assert\NotNull]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotNull]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'articles', cascade: ['persist']), ORM\JoinTable(name: 'mentions')]
    private Collection $mentions;

    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'consults')]
    private Collection $read_by;

    #[ORM\ManyToMany(targetEntity: Categories::class, inversedBy: 'articles', cascade: ['persist']), ORM\JoinTable(name: 'includes')]
    private Collection $includes;

    #[ORM\ManyToMany(targetEntity: Tags::class, inversedBy: 'articles', cascade: ['persist']), ORM\JoinTable(name: 'concerns')]
    private Collection $concerns;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Opinions::class, orphanRemoval: true)]
    private Collection $opinions;

    #[ORM\OneToMany(mappedBy: 'from_article', targetEntity: Comments::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\Column(nullable: true)]
    private ?float $version = null;

    public function __construct()
    {
        $this->mentions = new ArrayCollection();
        $this->read_by = new ArrayCollection();
        $this->includes = new ArrayCollection();
        $this->concerns = new ArrayCollection();
        $this->opinions = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

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
    public function setMentions(ArrayCollection $mentions): self
    {
        $this->mentions = $mentions;
        return $this;
    }
    
    public function getMentions(): Collection
    {
        return $this->mentions;
    }

    public function addMention(Users $user): self
    {
        //dd($this);
        if (!$this->mentions->contains($user)) {
            $this->mentions->add($user);
            $user->addMentionedIn($this);
        }

        return $this;
    }

    public function removeMention(Users $user): self
    {
        if ($this->mentions->removeElement($user)) {
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

    public function setIncludes(ArrayCollection $includes): Collection
    {
        return $this->includes = $includes;
    }

    public function addIncludes(Categories $includes): self
    {
        if (!$this->includes->contains($includes)) {
            $this->includes->add($includes);
        }

        return $this;
    }

    public function removeIncludes(Categories $includes): self
    {
        $this->includes->removeElement($includes);

        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function setConcerns(ArrayCollection $tags): self
    {
        $this->concerns = $tags;
        return $this;
    }
    
    public function getConcerns(): Collection
    {
        return $this->concerns;
    }

    public function addConcerns(Tags $concerns): self
    {
        if (!$this->concerns->contains($concerns)) {
            $this->concerns->add($concerns);
        }

        return $this;
    }

    public function removeConcerns(Tags $concerns): self
    {
        $this->concerns->removeElement($concerns);

        return $this;
    }

    /**
     * @return Collection<int, Opinions>
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinions $refersTo): self
    {
        if (!$this->opinions->contains($refersTo)) {
            $this->opinions->add($refersTo);
            $refersTo->setArticle($this);
        }

        return $this;
    }

    public function removeOpinion(Opinions $refersTo): self
    {
        if ($this->opinions->removeElement($refersTo)) {
            // set the owning side to null (unless already changed)
            if ($refersTo->getArticle() === $this) {
                $refersTo->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setFromArticle($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getFromArticle() === $this) {
                $comment->setFromArticle(null);
            }
        }

        return $this;
    }

    public function getVersion(): ?float
    {
        return $this->version;
    }

    public function setVersion(?float $version): self
    {
        $this->version = $version;

        return $this;
    }
}
