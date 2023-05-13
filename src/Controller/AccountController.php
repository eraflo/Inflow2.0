<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account/{id}', name: 'app_account')]
    public function showAccount(EntityManagerInterface $em, int $id, Request $request, Security $security): Response
    {
        $user = $em->getRepository(Users::class)->find($id);
        if (!$user) {
            //return $this->redirectToRoute('app_not_found');
        }
        $referer = $request->headers->get('referer');
        //$articles = $user->getArticles();
         
        return $this->render('account/index.html.twig', [
            'user' => $user,
            'referer' => $referer,
            /* 'articles' => $user->getArticles(),
            'socials' => $user->getOwns(),
            'followers' => $user->getFollowers(),
            'following' => $user->getFollows(),
            'subscriptions' => $user->getSubscribed() */
        ]);
    }
}
