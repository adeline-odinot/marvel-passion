<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
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
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $email = $utils->getLastUsername();
        
        return $this->render('user_account/login.html.twig',
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
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new Users();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

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
        
        return $this->render('user_account/registration.html.twig', [
            'formRegister' => $form->createView()
        ]);   
    }

    /**
     * Permet l'accès profil utilisateur ainsi que sa modification
     *
     * @Route("account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function updateProfile(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $request->files->get('account')['avatar'];

            $avatar_directory = $this->getParameter('avatar_directory');

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $avatar_directory,
                $fileName
            );

            $user->setAvatar($fileName);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre profil a été modifié avec succès !"
            );
        }

        return $this->render('user_account/userProfile.html.twig', [
                'formProfile' => $form->createView()
        ]); 
    }

    /**
     * Permet de modifier le mot de passe utilisateur
     * 
     * @Route("account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
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
                $form->get('oldPassword')->addError(new FormError('Le mot de passe que vous avez entré n\'est pas votre mot de passe actuel'));
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

        return $this->render('user_account/userPassword.html.twig',
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
        return $this->render('user/index.html.twig',
        [
            'user' => $this->getUser()
        ]);
    }
}
