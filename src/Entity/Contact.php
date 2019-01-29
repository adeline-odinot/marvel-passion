<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    /**
     * 
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100, minMessage="Votre prénom doit être supérieur à 2 caractères.", maxMessage="Votre prénom doit être inférieur à 100 caractères.")
     */

     private $firstname;

    /**
     * 
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100, minMessage="Votre nom doit être supérieur à 2 caractères.",  maxMessage="Votre nom doit être inférieur à 100 caractères.")
     */

    private $lastname;

    /**
     * 
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email(message="Vous devez entrer une adresse e-mail valide.")
     */

    private $email;

    /**
     * 
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=255, minMessage="Votre sujet doit être supérieur à 5 caractères.",  maxMessage="Votre sujet doit être inférieur à 255 caractères.")
     */

    private $subject;

    /**
     * 
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=10, minMessage="Votre message doit être supérieur à 2 caractères.")
     */

    private $message;

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
    
    /**
     * @param string|null $firstname
     * @return Contact
     */
    public function setFirstname(?string $firstname) : Contact
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }
    
    /**
     * @param string|null $lastname
     * @return Contact
     */
    public function setLastname(?string $lastname) : Contact
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return Contact
     */
    public function setEmail(?string $email): Contact
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }
    
    /**
     * @param string|null $subject
     * @return Contact
     */
    public function setSubject(?string $subject): Contact
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
    
    /**
     * @param string|null $message
     * @return Contact
     */
    public function setMessage(?string $message): Contact
    {
        $this->message = $message;

        return $this;
    }
}