<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// Class ProfilUserController (public: ProfilUser)  avec une route commune: /profil/user  
/**
 * @Route("/profil/user")
 */
class ProfilUserController extends AbstractController
{
    // Action show accessible par la route /profil/user/ --------------------------------- show
    // show = afficher le profil utilisateur
    /**
     * @Route("/", name="app_profil_user_show", methods={"GET"})
     */
    public function show(): Response
    {
        // récupérer le user connecté
        $user = $this->getUser(); // User connecté

        // render rendre la PAGE profil_user / show
        return $this->render('profil_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    // Action edit accessible par la route /profil/user/edit ----------------------------- edit
    // edit = modifier le profil utilisateur
    /**
     * @Route("/edit", name="app_profil_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UserRepository $userRepository,
                         UserPasswordHasherInterface $passwordHasher): Response
    {
        // récupérer le user connecté
        $user = $this->getUser();                               // User connecté
        $form = $this->createForm(User1Type::class, $user);     // create Formulaire: User1Type 
        $form->handleRequest($request);

        // FORMULAIRE Soumis & prêt à être traité
        if ($form->isSubmitted() && $form->isValid()) {
            // encodage du password:
            $pass = $form->get('pass')->getData(); // récupère le pass en clair
            // hash the password 
            $hashedPassword = $passwordHasher->hashPassword($user, $pass);
            $user->setPassword($hashedPassword);            

            $userRepository->add($user, true); // ADD en BDD
            // redirection vers show
            return $this->redirectToRoute('app_profil_user_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
