<?php

namespace App\MarvelPassion\ArticleBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\MarvelPassion\ArticleBundle\Repository\HumorRepository")
 * @UniqueEntity(fields={"title"}, message="Un autre article d'humour possède déjà ce titre, merci de bien vouloir le modifier")
 */
class Humor
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
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\MarvelPassion\CommentBundle\Entity\Comments", mappedBy="humor")
     * @ORM\OrderBy({"creationDate" = "DESC"})
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\MarvelPassion\UserBundle\Entity\Users", inversedBy="humors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;


    public function __construct()
    {
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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
            $comment->setHumor($this);
        }

        return $this;
    }

    public function removeComment(\App\MarvelPassion\CommentBundle\Entity\Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getHumor() === $this) {
                $comment->setHumor(null);
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
