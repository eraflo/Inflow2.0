<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 4095)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $author = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Articles $from_article = null;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: Opinions::class, orphanRemoval: true)]
    private Collection $opinions;

    private array $opinionSum = [];

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'replies')]
    private ?self $replies_to = null;

    #[ORM\OneToMany(mappedBy: 'replies_to', targetEntity: self::class, orphanRemoval: true)]
    private Collection $replies;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'comment_mentions')]
    private Collection $mentions;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
        $this->mentions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?Users
    {
        return $this->author;
    }

    public function setAuthor(?Users $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getFromArticle(): ?Articles
    {
        return $this->from_article;
    }

    public function setFromArticle(?Articles $from_article): self
    {
        $this->from_article = $from_article;

        return $this;
    }

    /**
     * @return Collection<int, Opinions>
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinions $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions->add($opinion);
            $opinion->setComment($this);
        }

        return $this;
    }

    public function removeOpinion(Opinions $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getComment() === $this) {
                $opinion->setComment(null);
            }
        }

        return $this;
    }

    public function getOpinionSum(): array {
        $opinions = $this->opinions;
        $opinionSum = [
            'likes' => 0,
            'dislikes' => 0,
        ];
        foreach ($opinions as $opinion) {
            if ($opinion->getOpinionValue() === 1) {
                $opinionSum['likes']++;
            } else {
                $opinionSum['dislikes']++;
            }
        }
        $this->opinionSum = $opinionSum;
        return $this->opinionSum;
    }

    public function getRepliesTo(): ?self
    {
        return $this->replies_to;
    }

    public function setRepliesTo(?self $replies_to): self
    {
        $this->replies_to = $replies_to;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addreply(self $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies->add($reply);
            $reply->setRepliesTo($this);
        }

        return $this;
    }

    public function removeReply(self $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getRepliesTo() === $this) {
                $reply->setRepliesTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getMentions(): Collection
    {
        return $this->mentions;
    }

    public function addMention(Users $mention): self
    {
        if (!$this->mentions->contains($mention)) {
            $this->mentions->add($mention);
        }

        return $this;
    }

    public function removeMention(Users $mention): self
    {
        $this->mentions->removeElement($mention);

        return $this;
    }

    public function setMentions(ArrayCollection $mentions): self
    {
        foreach($mentions as $mention) {
            if (!$this->mentions->contains($mention)) {
                $this->mentions->add($mention);
            }
        }

        return $this;
    }
}
