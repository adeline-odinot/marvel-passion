<?php

namespace App\MarvelPassion\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\MarvelPassion\CommentBundle\Repository\CommentsRepository")
 */
class Comments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\MarvelPassion\UserBundle\Entity\Users", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\MarvelPassion\ArticleBundle\Entity\Movies", inversedBy="comments")
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="App\MarvelPassion\ArticleBundle\Entity\Series", inversedBy="comments")
     */
    private $series;

    /**
     * @ORM\ManyToOne(targetEntity="App\MarvelPassion\ArticleBundle\Entity\Humor", inversedBy="comments")
     */
    private $humor;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez entrer votre commentaire.")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?\App\MarvelPassion\UserBundle\Entity\Users
    {
        return $this->user;
    }

    public function setUser(?\App\MarvelPassion\UserBundle\Entity\Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMovie(): ?\App\MarvelPassion\ArticleBundle\Entity\Movies
    {
        return $this->movie;
    }

    public function setMovie(?\App\MarvelPassion\ArticleBundle\Entity\Movies $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getSeries(): ?\App\MarvelPassion\ArticleBundle\Entity\Series
    {
        return $this->series;
    }

    public function setSeries(?\App\MarvelPassion\ArticleBundle\Entity\Series $series): self
    {
        $this->series = $series;

        return $this;
    }

    public function getHumor(): ?\App\MarvelPassion\ArticleBundle\Entity\Humor
    {
        return $this->humor;
    }

    public function setHumor(?\App\MarvelPassion\ArticleBundle\Entity\Humor $humor): self
    {
        $this->humor = $humor;

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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}
