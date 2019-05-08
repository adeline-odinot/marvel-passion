<?php

namespace App\Service;

use Twig\Environment;
use App\MarvelPassion\ContactBundle\Entity\Contact;


class ContactNotification
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;


    /**
     * @var Environment
     */
    private $template;

    public function __construct(\Swift_Mailer $mailer, Environment $template)
    {
        $this->mailer = $mailer;
        $this->template = $template;
    }

    public function notify(\App\MarvelPassion\ContactBundle\Entity\Contact $contact)
    {
        $message = (new \Swift_Message('Marvel-Passion'))
            ->setFrom($contact->getEmail())
            ->setTo('formation@adeline-odinot.com')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->template->render('ContactBundle/Ressources/views/Front/email.html.twig',
            [
                'contact' => $contact,
            ]), 'text/html'
        );
        $this->mailer->send($message);
    }
}