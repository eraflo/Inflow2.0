<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles/{id}', name: 'app_article', requirements:['id'=>'\d+'], defaults:['id'=>1], methods: ['GET'])]
    public function index($id, ArticlesRepository $repo): Response
    {
        $article = $repo->find($id);

        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/articles', name: 'app_articles', methods: ['GET'])]
    public function articles(ArticlesRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
        ]);
    }
}
