<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
/**
 * Summary of Users
 */
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 20)]
    private ?string $username = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $url = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Articles::class, orphanRemoval: true)]
    private Collection $articles;

    #[ORM\ManyToMany(targetEntity: Articles::class, mappedBy: 'mentions')]
    private Collection $mentions;

    #[ORM\ManyToMany(targetEntity: Articles::class, inversedBy: 'read_by'), ORM\JoinTable(name: 'consults')]
    private Collection $consults;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Opinions::class, orphanRemoval: true)]
    private Collection $rated;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'followers'), ORM\JoinTable(name: 'follows')]
    private Collection $follows;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'follows')]
    private Collection $followers;

    #[ORM\ManyToMany(targetEntity: Preferences::class, inversedBy: 'user'), ORM\JoinTable(name: 'set_')]
    private Collection $set_;

    #[ORM\ManyToMany(targetEntity: Roles::class, inversedBy: 'user'), ORM\JoinTable(name: 'has')]
    private Collection $has;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Socials::class, orphanRemoval: true)]
    private Collection $owns;

    #[ORM\ManyToMany(targetEntity: Categories::class, inversedBy: 'users'), ORM\JoinTable(name: 'subscriptions')]
    private Collection $subscriptions;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comments::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(nullable: true)]
    private ?int $follows_count = null;

    #[ORM\Column(nullable: true)]
    private ?int $followers_count = null;

    #[ORM\ManyToMany(targetEntity: Comments::class, mappedBy: 'mentions')]
    private Collection $comment_mentions;

    /* #[ORM\ManyToMany(targetEntity: Ranks::class, inversedBy: 'users')]
    private Collection $ranks; */

    public function __construct()
    {
        $this->follows_count = 0;
        $this->followers_count = 0;

        $this->articles = new ArrayCollection();
        $this->mentions = new ArrayCollection();
        $this->consults = new ArrayCollection();
        $this->rated = new ArrayCollection();
        $this->follows = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->set_ = new ArrayCollection();
        $this->has = new ArrayCollection();
        $this->owns = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->comment_mentions = new ArrayCollection();
    }

    public function getRoles(): array
    {
        //from https://symfony.com/doc/current/security.html#roles
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

/*     public function getRanks(): Collection
    {
        $this->ranks[] = 'ROLE_USER';
        return $this->ranks;
    }

    public function addRank(Ranks $ranks): self
    {
        if (!$this->ranks->contains($ranks)) {
            $this->ranks->add($ranks);
        }

        return $this;
    }

    public function removeRank(Ranks $ranks): self
    {
        $this->ranks->removeElement($ranks);

        return $this;
    } */

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getMentionedIn(): Collection
    {
        return $this->mentions;
    }

    public function addMentionedIn(Articles $mentionedIn): self
    {
        if (!$this->mentions->contains($mentionedIn)) {
            $this->mentions->add($mentionedIn);
        }

        return $this;
    }

    public function removeMentionedIn(Articles $mentionedIn): self
    {
        $this->mentions->removeElement($mentionedIn);

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getConsults(): Collection
    {
        return $this->consults;
    }

    public function addConsult(Articles $consult): self
    {
        if (!$this->consults->contains($consult)) {
            $this->consults->add($consult);
        }

        return $this;
    }

    public function removeConsult(Articles $consult): self
    {
        $this->consults->removeElement($consult);

        return $this;
    }

    /**
     * @return Collection<int, Opinions>
     */
    public function getRated(): Collection
    {
        return $this->rated;
    }

    public function addRated(Opinions $rated): self
    {
        if (!$this->rated->contains($rated)) {
            $this->rated->add($rated);
            $rated->setUser($this);
        }

        return $this;
    }

    public function removeRated(Opinions $rated): self
    {
        if ($this->rated->removeElement($rated)) {
            // set the owning side to null (unless already changed)
            if ($rated->getUser() === $this) {
                $rated->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollows(): Collection
    {
        return $this->follows;
    }

    public function addFollow(self $follow): self
    {
        if (!$this->follows->contains($follow)) {
            $this->follows->add($follow);
        }

        return $this;
    }

    public function removeFollow(self $follow): self
    {
        $this->follows->removeElement($follow);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(self $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers->add($follower);
            $follower->addFollow($this);
        }

        return $this;
    }

    public function removeFollower(self $follower): self
    {
        if ($this->followers->removeElement($follower)) {
            $follower->removeFollow($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Preferences>
     */
    public function getSet(): Collection
    {
        return $this->set_;
    }

    public function addSet(Preferences $set): self
    {
        if (!$this->set_->contains($set)) {
            $this->set_->add($set);
        }

        return $this;
    }

    public function removeSet(Preferences $set): self
    {
        $this->set_->removeElement($set);

        return $this;
    }

    /**
     * @return Collection<int, Roles>
     */
    public function getHas(): Collection
    {
        return $this->has;
    }

    public function addHas(Roles $has): self
    {
        if (!$this->has->contains($has)) {
            $this->has->add($has);
        }

        return $this;
    }

    public function removeHas(Roles $has): self
    {
        $this->has->removeElement($has);

        return $this;
    }

    /**
     * @return Collection<int, Socials>
     */
    public function getOwns(): Collection
    {
        return $this->owns;
    }

    public function addOwn(Socials $own): self
    {
        if (!$this->owns->contains($own)) {
            $this->owns->add($own);
            $own->setUser($this);
        }

        return $this;
    }

    public function removeOwn(Socials $own): self
    {
        if ($this->owns->removeElement($own)) {
            // set the owning side to null (unless already changed)
            if ($own->getUser() === $this) {
                $own->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Categories $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
        }

        return $this;
    }

    public function removeSubscription(Categories $subscription): self
    {
        $this->subscriptions->removeElement($subscription);

        return $this;
    }

    public function addHa(Roles $ha): self
    {
        if (!$this->has->contains($ha)) {
            $this->has->add($ha);
        }

        return $this;
    }

    public function removeHa(Roles $ha): self
    {
        $this->has->removeElement($ha);

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getFollowsCount(): ?int
    {
        return $this->follows_count;
    }

    public function setFollowsCount(?int $follows_count): self
    {
        $this->follows_count = $follows_count;

        return $this;
    }

    public function getFollowersCount(): ?int
    {
        return $this->followers_count;
    }

    public function setFollowersCount(?int $followers_count): self
    {
        $this->followers_count = $followers_count;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getCommentMentions(): Collection
    {
        return $this->comment_mentions;
    }

    public function addCommentMention(Comments $commentMention): self
    {
        if (!$this->comment_mentions->contains($commentMention)) {
            $this->comment_mentions->add($commentMention);
            $commentMention->addMention($this);
        }

        return $this;
    }

    public function removeCommentMention(Comments $commentMention): self
    {
        if ($this->comment_mentions->removeElement($commentMention)) {
            $commentMention->removeMention($this);
        }

        return $this;
    }

}
