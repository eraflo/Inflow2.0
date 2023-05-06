<?php

namespace App\Controller;

use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpotifyController extends AbstractController
{
    private const UserId = "ofyiw7jw61ak29tev5nqj2v2o";

    #[Route('/playlists', name: 'app_spotify_list', methods: ['GET'])]
    public function index(SpotifyWebAPI $api): Response
    {
        $playlists = $api->getUserPlaylists(self::UserId);
        $playlists = $playlists->items;

        return $this->render('spotify/index.html.twig', [
            'playlists' => $playlists,
        ]);
        
    }
    

    #[Route('/playlists/{name}', name: 'app_spotify', methods: ['GET'])]
    public function search(SpotifyWebAPI $api, $name): Response
    {
        $search = $api->search($name, 'playlist');

        $resultat = $search->playlists->items[0];


        return $this->render('spotify/playlist.html.twig', [
            'resultat' => $resultat,
        ]);
    }
}
