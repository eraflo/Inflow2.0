<?php

namespace App\Controller;

use Google_Client;
use Madcoda\Youtube;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YoutubeController extends AbstractController
{
    private const KEY = 'AIzaSyBl5TtalbP-Kg3iVgmbz40B8WIYcHd4YA4';
    private const CHANNEL_ID = 'UC7cUqgADmD2xV9VDlt6NOXg';
    private const nbVideoPerPages = 10;

    #[Route('/videos/youtube/{page}', name: 'app_youtube')]
    public function index($page = 1): Response
    {
        //$url = self::baseURL . 'search?part=snippet&channelId=' . self::CHANNEL_ID . '&order=date&type=video&key=' . self::KEY;
        //$url = file_get_contents($url);

        //$videos = json_decode($url);

        $youtube = new Youtube(['key' => self::KEY]);

        $offset = ($page - 1) * self::nbVideoPerPages;
        
        

        $videos = $youtube->searchChannelVideos([], self::CHANNEL_ID, $offset + self::nbVideoPerPages, Youtube::ORDER_DATE);
        
        $videos = array_slice($videos, $offset, self::nbVideoPerPages);

        $nbVideos = count($videos);

        return $this->render('youtube/index.html.twig', [
            'videos' => $videos,
            'page' => $page,
            'nbVideos' => $nbVideos
        ]);
    }
}
