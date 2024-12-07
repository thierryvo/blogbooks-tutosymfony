<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// Class AdminUserController (User de admin controller)------------------------
/**
 * @Route("/admin/user")
 */
class AdminUserController extends AbstractController
{
    // Action index accessible par la route /admin/user/ -------------------------------- index
    // index = lites des users
    /**
     * @Route("/", name="app_admin_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render(
            'admin_user/index.html.twig', 
            ['users' => $userRepository->findAll(),]
        );
    }

    // Action new accessible par la route /admin/user/new -------------------------------- new
    // new = creation utilisateur
    /**
     * @Route("/new", name="app_admin_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository,
                        UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user); // Creat Formulaire User type
        $form->handleRequest($request);

        // FORMULAIRE Soumis & prêt à être traité
        if ($form->isSubmitted() && $form->isValid()) {
            // encodage du password:
            $pass = $form->get('pass')->getData(); // récupère le pass en clair
            // hash the password 
            $hashedPassword = $passwordHasher->hashPassword($user, $pass);
            $user->setPassword($hashedPassword);

            $userRepository->add($user, true); // ADD en Bdd
            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rendre le formulaire de: SAISIE User
        return $this->renderForm(
            'admin_user/new.html.twig', 
            [
                'user' => $user,
                'form' => $form,
            ]
        );
    }

    // Action show accessible par la route /admin/user/{id} ------------------------------ show
    // show = afficher un utilisateur
    /**
     * @Route("/{id}", name="app_admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render(
            'admin_user/show.html.twig', 
            ['user' => $user,]
        );
    }

    // Action edit accessible par la route /admin/user/{id}/edit -------------------------- edit
    // edit = modifier un utilisateur
    /**
     * @Route("/{id}/edit", name="app_admin_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository,
                         UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user); // Creat Formulaire User type
        $form->handleRequest($request);

        // FORMULAIRE Soumis & prêt à être traité
        if ($form->isSubmitted() && $form->isValid()) {
            // encodage du password:
            $pass = $form->get('pass')->getData(); // récupère le pass en clair
            // hash the password 
            $hashedPassword = $passwordHasher->hashPassword($user, $pass);
            $user->setPassword($hashedPassword);            

            $userRepository->add($user, true); // ADD en BDD
            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // Action delete accessible par la route /admin/user/{id}/ --------------------------- delete
    // delete = supprimmer un utilisateur
    /**
     * @Route("/{id}", name="app_admin_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}