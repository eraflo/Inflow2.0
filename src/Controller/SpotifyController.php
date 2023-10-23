<?php

namespace App\Controller;

use Google\Service\CloudSearch\UserId;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpotifyController extends AbstractController
{
    private const UserId = "ofyiw7jw61ak29tev5nqj2v2o";
    private const UserIdSearch = "7djLavCbgtvH6E9nYkHWUU";

    // List of playlists
    #[Route('/playlists', name: 'app_spotify_list', methods: ['GET'])]
    public function index(SpotifyWebAPI $api): Response
    {
        // Mettre en cache les playlists
        $cache = new FilesystemAdapter();

        $playlists = $cache->getItem('spotify_playlists');
        if( !$playlists->isHit() ){
            // Récupérer les playlists
            $playlistsTemp = $api->getUserPlaylists(self::UserId);
            $playlistsTemp = $playlistsTemp->items;

            // Supprimer les playlists qui ne sont pas de l'utilisateur
            $id = 0;
            foreach($playlistsTemp as $playlist) {
                if($playlist->owner->id != self::UserId)
                    unset($playlistsTemp[$id]);
                $id++;
            }

            // Mettre en cache
            $playlists->set($playlistsTemp);
            $playlists->expiresAfter(1800);
            $cache->save($playlists);
        }
        

        return $this->render('spotify/index.html.twig', [
            'playlists' => $playlists->get(),
        ]);
        
    }
    
    // Affichage d'une Playlist
    #[Route('/playlists/{name}', name: 'app_spotify_playlist', methods: ['GET'], requirements: ['name' => '.+'])]
    public function search(SpotifyWebAPI $api, $name): Response
    {
        $search = $api->search($name, 'playlist');

        $playlist = $search->playlists->items[0];

        //voir à rajouter la liste des sons
        $music = $api->getPlaylistTracks($playlist->id);
        $music = $music->items;

        return $this->render('spotify/playlist.html.twig', [
            'resultat' => $playlist,
            'music' => $music,
        ]);
    }

    // Affichage des albums
    #[Route('/albums', name: 'app_spotify_albums', methods: ['GET'])]
    public function albums(SpotifyWebAPI $api): Response
    {
        $artist = $api->search('Inflow', 'artist');

        foreach($artist->artists->items as $art) {
            if($art->id == self::UserIdSearch) {
                $artist = $art;
                break;
            }
        }

        // cache des albums
        $cache = new FilesystemAdapter();

        $albums = $cache->getItem('spotify_albums');
        if(!$albums->isHit()) {
            $albumsTemp = $api->getArtistAlbums($artist->id);
            $albumsTemp = $albumsTemp->items;

            $albums->set($albumsTemp);
            $albums->expiresAfter(1800);
            $cache->save($albums);
        }


        return $this->render('spotify/albums.html.twig', [
            'albums' => $albums->get(),
        ]);
    }

    // Affichage d'un album
    #[Route('/albums/{name}', name: 'app_spotify_album', methods: ['GET'])]
    public function album(SpotifyWebAPI $api, $name): Response
    {
        $search = $api->search($name, 'album');

        $albumFin = null;

        foreach($search->albums->items as $album) {
            if($album->name == $name) {
                if($this->IsArtist($album->artists[0], 'Inflow')) {
                    $albumFin = $album;
                    break;
                }
            }
        }

        if($albumFin == null)
            return $this->redirectToRoute('app_spotify_albums');
        
        
        return $this->render('spotify/album.html.twig', [
            'resultat' => $albumFin,
        ]);

    }

    // Test si correspond à un artiste particulier
    private function IsArtist($artist, string $name) {
        return $artist->name == $name;
    }
}
