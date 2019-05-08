<?php

namespace App\MarvelPassion\UserBundle\Controller\Front;

use App\MarvelPassion\UserBundle\Entity\Users;
use App\Service\Upload;
use App\MarvelPassion\UserBundle\Form\AccountType;
use App\MarvelPassion\UserBundle\Entity\PasswordUpdate;
use App\MarvelPassion\UserBundle\Form\RegistrationType;
use App\MarvelPassion\UserBundle\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAccountController extends AbstractController
{
    /**
     * Permet de se connecter
     * 
     * @Route("/login", name="account_login")
     * 
     * @param AuthenticationUtils $utils
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $email = $utils->getLastUsername();
        
        return $this->render('UserBundle/Ressources/views/Front/user_account/login.html.twig',
            [
                'hasError' => $error!== null,
                'email' => $email
            ]
        );
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout() {}

    /**
     * Permet l'inscription aux utilisateurs
     *
     * @Route("/register", name="account_register")
     * 
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param Upload $upload
     * 
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, Upload $upload)
    {
        $user = new Users();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        $valid = true;

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Bienvenue sur Marvel-Passion <strong>{$user->getPseudo()}</strong> votre compte à bien été crée. Vous pouvez désormais vous connecter !"
            );

            return $this->redirectToRoute('account_login');
        }
        
        return $this->render('UserBundle/Ressources/views/Front/user_account/registration.html.twig', [
            'formRegister' => $form->createView()
        ]);   
    }

    /**
     * Permet l'accès profil utilisateur ainsi que sa modification
     *
     * @Route("account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * 
     * @param Request $request
     * @param ObjectManager $manager
     * @param Upload $upload
     * 
     * @return Response
     */
    public function updateProfile(Request $request, ObjectManager $manager, Upload $upload)
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        $valid = true;

        if($form->isSubmitted() && $form->isValid())
        {
            if(isset($request->files->get('account')['avatar']))
            {
                $fileName = $upload->upload($this->getParameter('avatar_directory'), $request->files->get('account')['avatar'], $user->getAvatar());

                if(!$fileName)
                {
                    $valid = false;
                    $form->get('avatar')->addError(new FormError("Le format d'image n'est pas accepté (jpg, jpeg, png)."));
                }
                else
                {
                    $user->setAvatar($fileName);
                }
            }
            if($valid)
            {
                $manager->persist($user);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    "Votre profil a été modifié avec succès !"
                );
            }
        }

        return $this->render('UserBundle/Ressources/views/Front/user_account/userProfile.html.twig', [
                'formProfile' => $form->createView()
        ]); 
    }

    /**
     * Permet de modifier le mot de passe utilisateur
     * 
     * @Route("account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     * 
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash()))
            {
                $form->get('oldPassword')->addError(new FormError('Le mot de passe que vous avez entré n\'est pas votre mot de passe actuel.'));
            }
            else
            {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié.'
                );

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('UserBundle/Ressources/views/Front/user_account/userPassword.html.twig',
        [
            'formUpdatePassword' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     * 
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     * 
     */
    public function myAccount()
    {
        return $this->render('UserBundle/Ressources/views/Front/user/index.html.twig',
        [
            'user' => $this->getUser()
        ]);
    }
}