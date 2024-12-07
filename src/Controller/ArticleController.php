<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Class ArticleController (public:  Article)  avec une route commune: /article
/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    // Action index accessible par la route /article/ ------------------------------------------- index
    //        index = faire la liste de toutes les articles
    /**
     * @Route("/", name="app_article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render(
            'article/index.html.twig', 
            ['articles' => $articleRepository->findAll(),]
        );
    }

    // Action show accessible par la route /article/{id} --------------------------------------- show
    //        show = montrer le détail d'un article
    /**
     * @Route("/{id}", name="app_article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        // récupérer les commentaires de l'article
        $commentaires = $article->getCommentaires();

        // render rendre la PAGE  article / show.html.twig
        return $this->render(
            'article/show.html.twig', 
            [
                'article' => $article,
                'commentaires' => $commentaires,
            ]
        );
    }
}
