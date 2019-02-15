<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShootingsRepository")
 */
class Shootings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez entrer le titre.")
     * @Assert\Length(min=8, max=255, minMessage="Le titre doit avoir au minimum 8 caractères.", maxMessage="Le titre doit avoir au maximum 255 caractères.")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=40, minMessage="La description doit avoir au minimum 40 caractères.")
     * @Assert\NotBlank(message="Vous devez entrer la description du lieu de tournage.")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, minMessage="L'adresse doit contenir au minimum 5 caractères.")
     * @Assert\NotBlank(message="Vous devez entrer l'adresse du lieu de tournage.")
     */
    private $address;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Vous devez entrer la latitude du lieu de tournage.")
     */
    private $lat;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Vous devez entrer la longitude du lieu de tournage.")
     */
    private $lng;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movies", inversedBy="shootings")
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Series", inversedBy="shootings")
     */
    private $serie;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getMovie(): ?Movies
    {
        return $this->movie;
    }

    public function setMovie(?Movies $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getSerie(): ?Series
    {
        return $this->serie;
    }

    public function setSerie(?Series $serie): self
    {
        $this->serie = $serie;

        return $this;
    }
}
