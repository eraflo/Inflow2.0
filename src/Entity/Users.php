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

    #[ORM\ManyToMany(targetEntity: Articles::class, inversedBy: 'users'), ORM\JoinTable(name: 'mentioned_in')]
    private Collection $mentioned_in;

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

    #[ORM\ManyToMany(targetEntity: Ranks::class, inversedBy: 'user'), ORM\JoinTable(name: 'has')]
    private Collection $has;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Socials::class, orphanRemoval: true)]
    private Collection $owns;

    #[ORM\ManyToMany(targetEntity: Categories::class, inversedBy: 'users'), ORM\JoinTable(name: 'suscribed')]
    private Collection $suscribed;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->mentioned_in = new ArrayCollection();
        $this->consults = new ArrayCollection();
        $this->rated = new ArrayCollection();
        $this->follows = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->set_ = new ArrayCollection();
        $this->has = new ArrayCollection();
        $this->owns = new ArrayCollection();
        $this->suscribed = new ArrayCollection();
    }

    public function getRoles() : array
    {
        return ['ROLE_USER'];
    }

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
        return $this->mentioned_in;
    }

    public function addMentionedIn(Articles $mentionedIn): self
    {
        if (!$this->mentioned_in->contains($mentionedIn)) {
            $this->mentioned_in->add($mentionedIn);
        }

        return $this;
    }

    public function removeMentionedIn(Articles $mentionedIn): self
    {
        $this->mentioned_in->removeElement($mentionedIn);

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
     * @return Collection<int, Ranks>
     */
    public function getHas(): Collection
    {
        return $this->has;
    }

    public function addHas(Ranks $has): self
    {
        if (!$this->has->contains($has)) {
            $this->has->add($has);
        }

        return $this;
    }

    public function removeHas(Ranks $has): self
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
    public function getSuscribed(): Collection
    {
        return $this->suscribed;
    }

    public function addSuscribed(Categories $suscribed): self
    {
        if (!$this->suscribed->contains($suscribed)) {
            $this->suscribed->add($suscribed);
        }

        return $this;
    }

    public function removeSuscribed(Categories $suscribed): self
    {
        $this->suscribed->removeElement($suscribed);

        return $this;
    }

    public function addHa(Ranks $ha): self
    {
        if (!$this->has->contains($ha)) {
            $this->has->add($ha);
        }

        return $this;
    }

    public function removeHa(Ranks $ha): self
    {
        $this->has->removeElement($ha);

        return $this;
    }
}
