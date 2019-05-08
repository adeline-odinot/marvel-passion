<?php

namespace App\MarvelPassion\ArticleBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\MarvelPassion\ArticleBundle\Repository\SeriesRepository")
 * @UniqueEntity(fields={"title"}, message="Un autre article de série possède déjà ce titre, merci de bien vouloir le modifier")
 */
class Series
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez entrer le titre de l'article.")
     * @Assert\Length(min=4, max=255, minMessage="Le titre doit avoir au minimum 4 caractères.", maxMessage="Le titre doit avoir au maximum 255 caractères.")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=40, minMessage="Le contenu doit avoir au minimum 40 caractères.")
     * @Assert\NotBlank(message="Vous devez entrer le contenu de l'article.")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\OneToMany(targetEntity="App\MarvelPassion\CommentBundle\Entity\Comments", mappedBy="series")
     * @ORM\OrderBy({"creationDate" = "DESC"})
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\MarvelPassion\UserBundle\Entity\Users", inversedBy="series")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=20, max=300, minMessage="L'introduction doit avoir au minimum 20 caractères.", maxMessage="L'introduction doit avoir au maximum 300 caractères.")
     * @Assert\NotBlank(message="Vous devez entrer l'introduction de l'article.")
     */
    private $introduction;

    /**
     * @ORM\OneToMany(targetEntity="App\MarvelPassion\ShootingBundle\Entity\Shootings", mappedBy="serie")
     */
    private $shootings;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->shootings = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(\App\MarvelPassion\CommentBundle\Entity\Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setSeries($this);
        }

        return $this;
    }

    public function removeComment(\App\MarvelPassion\CommentBundle\Entity\Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getSeries() === $this) {
                $comment->setSeries(null);
            }
        }

        return $this;
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

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * @return Collection|Shootings[]
     */
    public function getShootings(): Collection
    {
        return $this->shootings;
    }

    public function addShooting(\App\MarvelPassion\ShootingBundle\Entity\Shootings $shooting): self
    {
        if (!$this->shootings->contains($shooting)) {
            $this->shootings[] = $shooting;
            $shooting->setSerie($this);
        }

        return $this;
    }

    public function removeShooting(\App\MarvelPassion\ShootingBundle\Entity\Shootings $shooting): self
    {
        if ($this->shootings->contains($shooting)) {
            $this->shootings->removeElement($shooting);
            // set the owning side to null (unless already changed)
            if ($shooting->getSerie() === $this) {
                $shooting->setSerie(null);
            }
        }

        return $this;
    }
}
