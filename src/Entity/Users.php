<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity(
 *  fields={"email"},
 *  message="Un autre utilisateur s'est déjà inscrit avec cette adresse e-mail. Merci de bien vouloir la modifier."
 * )
 *  @UniqueEntity(
 *  fields={"pseudo"},
 *  message="Un autre utilisateur s'est déjà inscrit avec ce pseudo. Merci de bien vouloir le modifier."
 * )
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre prénom.")
     * @Assert\Length(min=2, max=255, minMessage="Votre prénom avoir au minimum 2 caractères.", maxMessage="Votre prénom doit avoir au maximum 255 caractères.")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre nom de famille.")
     * @Assert\Length(min=2, max=255, minMessage="Votre nom de famille doit avoir au minimum 2 caractères.", maxMessage="Votre nom de famille doit avoir au maximum 255 caractères.")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre pseudo.")
     * @Assert\Length(min=2, max=255, minMessage="Votre pseudo doit avoir au minimum 2 caractères.", maxMessage="Votre pseudo doit avoir au maximum 255 caractères.")
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit avoir au minimum 8 caractères.")
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre adresse e-mail.")
     * @Assert\Email(message="Veuillez renseigner une adresse e-mail valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Assert\EqualTo(propertyPath="hash", message="Votre mot de passe n'est pas identique.")
     */
    private $passwordConfirm;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Humor", mappedBy="user", orphanRemoval=true)
     */
    private $humors;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Movies", mappedBy="user", orphanRemoval=true)
     */
    private $movies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Series", mappedBy="user", orphanRemoval=true)
     */
    private $series;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->humors = new ArrayCollection();
        $this->movies = new ArrayCollection();
        $this->series = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
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

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->userRoles->map(function($role)
        {
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function getPassword()
    {
        return $this->hash;
    }

    public function getSalt() {}

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials() {}

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Humor[]
     */
    public function getHumors(): Collection
    {
        return $this->humors;
    }

    public function addHumor(Humor $humor): self
    {
        if (!$this->humors->contains($humor)) {
            $this->humors[] = $humor;
            $humor->setUser($this);
        }

        return $this;
    }

    public function removeHumor(Humor $humor): self
    {
        if ($this->humors->contains($humor)) {
            $this->humors->removeElement($humor);
            // set the owning side to null (unless already changed)
            if ($humor->getUser() === $this) {
                $humor->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Movies[]
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movies $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->setUser($this);
        }

        return $this;
    }

    public function removeMovie(Movies $movie): self
    {
        if ($this->movies->contains($movie)) {
            $this->movies->removeElement($movie);
            // set the owning side to null (unless already changed)
            if ($movie->getUser() === $this) {
                $movie->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Series[]
     */
    public function getSeries(): Collection
    {
        return $this->series;
    }

    public function addSeries(Series $series): self
    {
        if (!$this->series->contains($series)) {
            $this->series[] = $series;
            $series->setUser($this);
        }

        return $this;
    }

    public function removeSeries(Series $series): self
    {
        if ($this->series->contains($series)) {
            $this->series->removeElement($series);
            // set the owning side to null (unless already changed)
            if ($series->getUser() === $this) {
                $series->setUser(null);
            }
        }

        return $this;
    }
}
