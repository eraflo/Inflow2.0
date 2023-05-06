<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\CreateArticleFormType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/articles/create', name: 'app_article_create', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $em): Response
    {
        if($this->getUser() == null)
            return $this->redirectToRoute('app_login');
        
        $article = new Articles();

        $articleForm = $this->createForm(CreateArticleFormType::class, $article);
        

        if($articleForm->isSubmitted() && $articleForm->isValid())
        {
            $article->setUser($this->getUser());
            $article->setReleaseDate(new \DateTime());
            $article->setContent($articleForm->get('content')->getData());
            $article->setTitle($articleForm->get('title')->getData());
            $article->addInclude($articleForm->get('category')->getData());
            foreach($articleForm->get('tags')->getData() as $tag)
                $article->addConcern($tag);
            
            $article->setDescription(substr($articleForm->get('content')->getData(), 0, 200));

            if($articleForm->get('mentions')->getData() != null)
                foreach($articleForm->get('mentions')->getData() as $user)
                    $article->addUser($user);
            
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('app_article', ['id'=>$article->getId()]);
        }

        return $this->render('article/create.html.twig',  [
            'articleForm' => $articleForm,
        ]);
    }


}