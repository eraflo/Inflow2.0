<?php

namespace App\Controller;

use Google\Service\CloudSearch\UserId;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SpotifyController extends AbstractController
{
    private const UserId = "ofyiw7jw61ak29tev5nqj2v2o";
    private const UserIdSearch = "7djLavCbgtvH6E9nYkHWUU";
    private $cache;
    private $spotifyApi;

    public function __construct(
        SpotifyWebAPI $spotifyApi
    )
    {
        $this->spotifyApi = $spotifyApi;
        $this->cache = new FilesystemAdapter('spotify', 1800);
    }

    private function getAndProcessUserPlaylists($userId, $playlistsCacheItem) {
        // Récupérer les playlists
        $playlistsTemp = $this->spotifyApi->getUserPlaylists($userId);
        $playlistsTemp = $playlistsTemp->items;

        // Supprimer les playlists qui ne sont pas de l'utilisateur
        foreach($playlistsTemp as $playlistKey => $playlist) {
            if ($playlist->owner->id != $userId) {
                unset($playlistsTemp[$playlistKey]);
            } else {
                // Generate URL for the playlist if it belongs to the user
                $playlistsTemp[$playlistKey]->path = $this->generateUrl('app_spotify_playlist', ['name' => $playlist->name]);
            }
        }

        // Mettre en cache
        $playlistsCacheItem->set($playlistsTemp);
        $playlistsCacheItem->expiresAfter(1800);
        $this->cache->save($playlistsCacheItem);
        return $playlistsCacheItem;
    }

    // List of playlists
    #[Route('/playlists', name: 'app_spotify_list', methods: ['GET'])]
    public function index(): Response
    {
        // Mettre en cache les playlists
        //$cache = new FilesystemAdapter('spotify');

        //$this->cache->delete('playlists');
        $playlists = $this->cache->getItem('playlists');
        if( !$playlists->isHit() ){
            $playlists = $this->getAndProcessUserPlaylists(self::UserId, $playlists);
        }

        //dd($playlists);
        

        return $this->render('spotify/index.html.twig', [
            'playlists' => $playlists->get(),
        ]);
        
    }
    
    // Affichage d'une Playlist
    #[Route('/playlists/{name}', name: 'app_spotify_playlist', methods: ['GET'], requirements: ['name' => '.+'])]
    public function search($name): Response
    {
        $playlists = $this->cache->getItem('playlists');
        
        if( !$playlists->isHit() ) {
            $playlists = $this->getAndProcessUserPlaylists(self::UserId, $playlists);
        }

        $playlist = array_filter($playlists->get(), function($playlist) use ($name) {
            return $playlist->name === $name;
        });
        $playlist = reset($playlist);

        // If the requested playlist does not exist (false in returned by reset() if the array is empty)
        if (!$playlist) {
            //handle error
            dd($playlist);
        }

        $tracks = $this->cache->getItem('playlist_'.$name.'_tracks');

        if( !$tracks->isHit() ){
            $tracks = $this->spotifyApi->getPlaylistTracks($playlist->id);
        }

        $tracks = $tracks->items;

        return $this->render('spotify/playlist.html.twig', [
            'playlist' => $playlist,
            'tracks' => $tracks,
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
        $cache = new FilesystemAdapter('spotify');

        $albums = $cache->getItem('albums');
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

    # Get the best albums in cache
    public static function GetBestAlbumInCache(SpotifyWebAPI $api) {
        $cache = new FilesystemAdapter();

        $albums = $cache->getItem('spotify_best_albums');
        if(!$albums->isHit()) {
            $api->setAccessToken($_ENV['GOOGLE_API_KEY']);

            $artist = $api->search('Inflow', 'artist');

            foreach($artist->artists->items as $art) {
                if($art->id == self::UserIdSearch) {
                    $artist = $art;
                    break;
                }
            }

            $albumsTemp = $api->getArtistAlbums($artist->id);
            $albumsTemp = $albumsTemp->items;

            $albums->set($albumsTemp);
            $albums->expiresAfter(1800);
            $cache->save($albums);
        }

        return $albums->get();
    }

    # Get the best playlists in cache
    public static function GetBestPlaylistInCache(SpotifyWebAPI $api) {
        $cache = new FilesystemAdapter();

        $playlists = $cache->getItem('spotify_best_playlists');
        if(!$playlists->isHit()) {
            # Use this : calliostro_spotify_web_api.token_provider
            

            $playlistsTemp = $api->getUserPlaylists(self::UserId);
            $playlistsTemp = $playlistsTemp->items;

            // Supprimer les playlists qui ne sont pas de l'utilisateur
            $id = 0;
            foreach($playlistsTemp as $playlist) {
                if($playlist->owner->id != self::UserId)
                    unset($playlistsTemp[$id]);
                $id++;
            }

            $playlists->set($playlistsTemp);
            $playlists->expiresAfter(1800);
            $cache->save($playlists);
        }

        return $playlists->get();
    }
}
