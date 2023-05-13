<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        //$userId = $request->getSession()->get("id");
        //dd($this->getUser());
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            //'id' => $userId
        ]);
    }
}
