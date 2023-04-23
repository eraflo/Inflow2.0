<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
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

    #[ORM\Column(length: 20)]
    private ?string $theme = null;

    #[ORM\Column(length: 40)]
    private ?string $font = null;

    #[ORM\Column(nullable: true)]
    private ?int $font_size = null;

    #[ORM\Column(length: 30)]
    private ?string $font_weight = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $url = null;

    public function __construct()
    {
        $this->theme = 'light';
        $this->font = 'Roboto';
        $this->font_size = 16;
        $this->font_weight = 'normal';
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

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getFont(): ?string
    {
        return $this->font;
    }

    public function setFont(string $font): self
    {
        $this->font = $font;

        return $this;
    }

    public function getFontSize(): ?int
    {
        return $this->font_size;
    }

    public function setFontSize(?int $font_size): self
    {
        $this->font_size = $font_size;

        return $this;
    }

    public function getFontWeight(): ?string
    {
        return $this->font_weight;
    }

    public function setFontWeight(string $font_weight): self
    {
        $this->font_weight = $font_weight;

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
}
