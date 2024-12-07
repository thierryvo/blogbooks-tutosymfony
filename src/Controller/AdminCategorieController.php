<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Class AdminCategorieController (Admin:  Categorie)  avec une route commune: /admin/categorie 
/**
 * @Route("/admin/categorie")
 */
class AdminCategorieController extends AbstractController
{
    // Action index accessible par la route /admin/categorie/ ------------------------------------------- index
    //        index = faire la liste de toutes les catégories
    /**
     * @Route("/", name="app_admin_categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('admin_categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    // Action new accessible par la route /admin/categorie/new ------------------------------------------ new
    //        new = créer une nouvelle categorie
    /**
     * @Route("/new", name="app_admin_categorie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategorieRepository $categorieRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->add($categorie, true);

            return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    // Action show accessible par la route /admin/categorie/{id} --------------------------------------- show
    //        show = montrer le détail d'une catégorie
    /**
     * @Route("/{id}", name="app_admin_categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('admin_categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    // Action edit accessible par la route /admin/categorie/{id}/edit ------------------------------------ edit
    //        edit = modifier une catégorie existante
    /**
     * @Route("/{id}/edit", name="app_admin_categorie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->add($categorie, true);

            return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    // Action delete accessible par la route /admin/categorie/{id}/delete -------------------------------- delete
    //        delete = supprimer une catégorie existante
    /**
     * @Route("/{id}", name="app_admin_categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        // suppression
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $categorieRepository->remove($categorie, true);
        }

        // redirection
        return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
