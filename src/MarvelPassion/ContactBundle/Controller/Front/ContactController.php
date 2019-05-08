<?php

namespace App\MarvelPassion\ContactBundle\Controller\Front;

use App\MarvelPassion\ContactBundle\Entity\Contact;
use App\MarvelPassion\ContactBundle\Form\ContactType;
use App\Service\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * Permet d'afficher la page de contact
     * 
     * @Route("/contact", name="contact")
     * 
     * @param Request $request
     * @param ContactNotification $notify
     * 
     * @return Response
     */
    public function index(Request $request, ContactNotification $notify)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $notify->notify($contact);
            
            $this->addFlash(
                'success',
                "Votre email a bien été envoyé, nous vous répondrons dans les plus bref délais."
            );
        }

        return $this->render('ContactBundle/Ressources/views/Front/contact.html.twig',
            [
                'formContact' => $form->createView()
            ]
        );
    }
}