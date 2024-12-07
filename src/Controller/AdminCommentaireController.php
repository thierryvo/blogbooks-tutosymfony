<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

// Class AdminCommentaireController (controller commentaire) -----------------------------------
/**
 * @Route("/admin/commentaire")
 */
class AdminCommentaireController extends AbstractController
{
    // Action index accessible par la route /admin/commentaire/ --------------------------- index
    // index = liste des commentaires
    /**
     * @Route("/", name="app_admin_commentaire_index", methods={"GET"})
     */
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('admin_commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    // Action new accessible par la route /admin/commentaire/new --------------------------- new
    // new = création commentaire
    /**
     * @Route("/new", name="app_admin_commentaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        $commentaire->setCreatedAt(new DateTimeImmutable());                // Date du jour 

        $form = $this->createForm(CommentaireType::class, $commentaire);    // Creat Formulaire Commentaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->add($commentaire, true);

            return $this->redirectToRoute('app_admin_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    // Action showw accessible par la route /admin/commentaire/{id} ----------------------- show
    // show = afficher le commentaire
    /**
     * @Route("/{id}", name="app_admin_commentaire_show", methods={"GET"})
     */
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('admin_commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    // Action edit accessible par la route /admin/commentaire/{id}edit -------------------- edit
    // edit = modifier le commentaire
    /**
     * @Route("/{id}/edit", name="app_admin_commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->add($commentaire, true);

            return $this->redirectToRoute('app_admin_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    // Action delete accessible par la route /admin/commentaire/{id} ---------------------- delete
    // delete = suppression d'un commentaire
    /**
     * @Route("/{id}", name="app_admin_commentaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $commentaireRepository->remove($commentaire, true);
        }

        return $this->redirectToRoute('app_admin_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
