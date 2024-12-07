<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\Categorie1Type;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Class CategorieController (public:  Categorie)  avec une route commune: /categorie
/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    // Action index accessible par la route /categorie/ ------------------------------------------- index
    //        index = faire la liste de toutes les catégories
    /**
     * @Route("/", name="app_categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    // Action show accessible par la route /categorie/{id} --------------------------------------- show
    //        show = montrer le détail d'une catégorie
    /**
     * @Route("/{id}", name="app_categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        // récupérer tous les Articles d'une Catégorie garce au getter
        $articles = $categorie->getArticles();

        // render affichage PAGE categorie / show.html.twig
        return $this->render(
            'categorie/show.html.twig', 
            [
                'categorie' => $categorie,
                'articles' => $articles,
            ]
        );
    }
}
