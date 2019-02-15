<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Users;
use App\Service\Paginator;
use App\Form\AdminUserType;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUsersController extends AbstractController
{
    /**
     * Permet d'afficher la page d'administration des utilisateurs
     * 
     * @Route("/admin/users", name="admin_users_index")
     *
     * @param UsersRepository $repo
     * @var $page
     * @param Paginator $paginator
     * 
     * @return void
     */
    public function index(UsersRepository $repo, $page = 1, Paginator $paginator)
    {
        $paginator->setEntityClass(Users::class)
                  ->setPage($page);

        return $this->render('admin/users/index.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * Permet de modifier un rôle utilisateur
     * 
     * @Route("/admin/users/{id}/editRole", name="edit_role")
     * 
     * @param Users $user
     * @param UsersRepository $repo
     * @var $id
     * @param Request $request
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function addRole(Users $user, UsersRepository $repo, $id, Request $request, ObjectManager $manager)
    {
        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
        $tabRole= [];
        foreach($roles as $role){
            $tabRole[$role->getTitle()] = $role->getId();
        }

        $form = $this->createForm(AdminUserType::class, $user, [
            'data' => $tabRole,'data_class' => null
        ]);

        $form->handleRequest($request);

        $user = $repo->find($id);

        if($form->isSubmitted() && $form->isValid())
        {
            $role = new Role();

            $roleIdForm = $form->get('role_id')->getData();
            $role =  $this->getDoctrine()->getRepository(Role::class)->find($roleIdForm);

            $user->addUserRole($role);
            

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le role de <strong>{$user->getPseudo()}</strong> a bien été ajouté !"
            );

            return $this->redirectToRoute('edit_role', ['id' => $user->getId()]);
        }

        return $this->render('admin/users/editRole.html.twig',
            [
                'tabRole' => $tabRole,
                'user' => $user,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * Permet de supprimer un role utilisateur
     *
     * @Route("/admin/users/{id}/{idRole}/deleteRole", name="delete_role")
     * 
     * @param Users $users
     * @param Role $idRole
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function deleteRole(Users $users, Role $idRole, ObjectManager $manager)
    {
        
        $users->removeUserRole($idRole);

        $manager->persist($users);
        $manager->flush();

        $this->addFlash(
        'success',
        "Le rôle '{$idRole->getTitle()}' de <strong>{$users->getPseudo()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute('edit_role', ['id' => $users->getId()]);
    }

    /**
     * Permet de supprimer un utilisateur
     *
     * @Route("/admin/users/{id}/deleteUser", name="delete_user")
     * 
     * @param Users $users
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function deleteUser(Users $users, ObjectManager $manager)
    {
        $manager->remove($users);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'utilisateur <strong>{$users->getPseudo()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute("admin_users_index");
    }
}