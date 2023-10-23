<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Récupérer les vidéos de la chaine youtube qui sont en cache
        $cache = new FilesystemAdapter();
        
        $videos = $cache->getItem('youtube_videos_page_1');
        if ($videos->isHit()) {
            // Prend les 10 premières vidéos
            $videos = array_slice($videos->get()['results'], 0, 10);
        } else {
            $videos = [];
        }

        

        // playlists en cache
        $playlists = $cache->getItem('spotify_playlists');
        if ($playlists->isHit()) {
            $playlists = array_slice($playlists->get(), 0, 5);
        } else {
            $playlists = [];
        }

        // albums en cache
        $albums = $cache->getItem('spotify_albums');
        if ($albums->isHit()) {
            $albums = array_slice($albums->get(), 0, 2);
        } else {
            $albums = [];
        }
        


        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'videos'=> $videos,
            'playlists'=> $playlists,
            'albums'=> $albums
        ]);
    }
}
