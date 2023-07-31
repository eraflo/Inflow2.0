<?php

namespace App\Controller;

use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SpotifyWebAPI $api): Response
    {
        $spotify = new SpotifyController();
        $playlists = $spotify->Playlists($api);
        // dd($playlists);

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'playlists' => $playlists,
        ]);
    }
}
