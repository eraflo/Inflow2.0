<?php

namespace App\Controller;

use Google\Service\YouTube\Playlist;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $playlists = $this->Playlists($api);

        return $this->render('spotify/index.html.twig', [
            'playlists' => $playlists,
        ]);
        
    }
    
    // Affichage d'une Playlist
    #[Route('/playlists/{name}', name: 'app_spotify_playlist', methods: ['GET'])]
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

        $albums = $api->getArtistAlbums($artist->id);
        $albums = $albums->items;


        return $this->render('spotify/albums.html.twig', [
            'albums' => $albums,
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

    // Playlists liste
    public function Playlists(SpotifyWebAPI $api) {
        $playlists = $api->getUserPlaylists(self::UserId);
        $playlists = $playlists->items;

        return $playlists;
    }

    // Test si correspond à un artiste particulier
    private function IsArtist($artist, string $name) {
        return $artist->name == $name;
    }
}
