<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\Commentaire1Type;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

// Class CommentaireController (public:  Commentaire)  avec une route commune: /article/commentaire 
/**
 * @Route("/article/commentaire")
 */
class CommentaireController extends AbstractController
{
    // Action new accessible par la route /article/commentaire/{id}/new ----------------------------- new
    // new = création commentaire
    /**
     * @Route("/{id}/new", name="app_commentaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, Article $article, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        $commentaire->setCreatedAt(new DateTimeImmutable());                // Date du jour 
        $commentaire->setUser($this->getUSer());                            // User connecté
        $commentaire->setArticle($article);                                 // article séletionné par param-converter

        $form = $this->createForm(Commentaire1Type::class, $commentaire);   // Creat Formulaire Commentaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->add($commentaire, true);                // ADD commentaire

            // Redirection vers: le détail de l'article - article show : ne pas oublier l'id nécessaire de l'article
            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    // Action edit accessible par la route /article/commentaire/{id}edit ------------------------------- edit
    // edit = modifier le commentaire
    /**
     * @Route("/{id}/edit", name="app_commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $form = $this->createForm(Commentaire1Type::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->add($commentaire, true);

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }


}
