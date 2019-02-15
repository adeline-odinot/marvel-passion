<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MoviesRepository")
 * @UniqueEntity(fields={"title"}, message="Un autre article de film possède déjà ce titre, merci de bien vouloir le modifier")
 */
class Movies
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
     * @Assert\Length(min=10, max=255, minMessage="Le titre doit avoir au minimum 10 caractères.", maxMessage="Le titre doit être inférieur à 255 caractères.")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez entrer le contenu de l'article.")
     * @Assert\Length(min=40, minMessage="Le contenu doit avoir au minimum 40 caractères.")
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
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="movie")
     * @ORM\OrderBy({"creationDate" = "DESC"})
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="movies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez entrer l'introduction de l'article.")
     * @Assert\Length(min=20, max=350, minMessage="L'introduction doit avoir au minimum 20 caractères.", maxMessage="L'introduction doit avoir au maximum 350 caractères.")
     */
    private $introduction;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Shootings", mappedBy="movie")
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

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setMovie($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getMovie() === $this) {
                $comment->setMovie(null);
            }
        }

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

    public function addShooting(Shootings $shooting): self
    {
        if (!$this->shootings->contains($shooting)) {
            $this->shootings[] = $shooting;
            $shooting->setMovie($this);
        }

        return $this;
    }

    public function removeShooting(Shootings $shooting): self
    {
        if ($this->shootings->contains($shooting)) {
            $this->shootings->removeElement($shooting);
            // set the owning side to null (unless already changed)
            if ($shooting->getMovie() === $this) {
                $shooting->setMovie(null);
            }
        }

        return $this;
    }
}
