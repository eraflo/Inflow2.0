<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SpotifyWebAPI\SpotifyWebAPI;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SpotifyWebAPI $api): Response
    {
        // Récupérer les vidéos de la chaine youtube qui sont en cache
        $cache = new FilesystemAdapter();
        
        $videos = $cache->getItem('youtube_best_videos');
        if ($videos->isHit()) {
            // Prend les 10 premières vidéos ['results']
            $videos = array_slice($videos->get(), 0, 10);
        } else {
            # Write "aa" at the top of the file
            echo "aa";
            $videos = YoutubeController::GetBestVideoInCache();
        }
        

        // playlists en cache
        $playlists = $cache->getItem('spotify_best_albums');
        if ($playlists->isHit()) {
            $playlists = array_slice($playlists->get(), 0, 5);
        } else {
            $playlists = SpotifyController::GetBestPlaylistInCache($api);
        }

        // albums en cache
        $albums = $cache->getItem('spotify_best_playlists');
        if ($albums->isHit()) {
            $albums = array_slice($albums->get(), 0, 2);
        } else {
            $albums = SpotifyController::GetBestAlbumInCache($api);
        }
        


        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'videos'=> $videos,
            'playlists'=> $playlists,
            'albums'=> $albums
        ]);
    }
}
