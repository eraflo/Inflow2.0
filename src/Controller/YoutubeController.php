<?php

namespace App\Controller;

use Google_Client;
use Madcoda\Youtube;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

class YoutubeController extends AbstractController
{
    private const KEY = 'AIzaSyBl5TtalbP-Kg3iVgmbz40B8WIYcHd4YA4';
    private const CHANNEL_ID = 'UC7cUqgADmD2xV9VDlt6NOXg';
    private const nbVideosPerRequest = 50;
    private const nbVideosPerPage = 10;

    #[Route('/videos/youtube/{page}', name: 'app_youtube')]
    public function index($page = 1): Response
    {

        $offset = ($page - 1) * self::nbVideosPerRequest;

        $cache = new FilesystemAdapter();

        $youtube = new Youtube(['key' => self::KEY]);

        $numberOfVideos = $cache->getItem('number_of_videos');
        if (!$numberOfVideos->isHit()) {
            $numberOfVideos->set((int) $youtube->getChannelById(self::CHANNEL_ID)->statistics->videoCount);
            $numberOfVideos->expiresAfter(1800);
            $cache->save($numberOfVideos);
        }

        //dd($numberOfVideos);

        //$cache->deleteItem('last_video');

        $lastVideo = $cache->getItem('last_video');
        if (!$lastVideo->isHit()) {
            $lastVideo->set($youtube->searchChannelVideos([], self::CHANNEL_ID, 1, Youtube::ORDER_DATE));
            $lastVideo->expiresAfter(1800);
            $cache->save($lastVideo);
        }

        //dd($lastVideo);

        //$cache->deleteItem('youtube_videos_page_1');

        //  only the number of pages / resultset returned by the api
        $numberOfPages = ceil($numberOfVideos->get() / self::nbVideosPerRequest);

        $videos = $cache->getItem('youtube_videos_page_1');
        $previousVideoNumber = $cache->getItem('previous_video_number');

        //dd($cache->getItem('youtube_videos_page_1')->get()['results'][0]);

        if (
            !$videos->isHit() ||
            $cache->getItem('youtube_videos_page_1')->get()['results'][0]->id->videoId !== $lastVideo->get()['results'][0]->id->videoId ||
            !$previousVideoNumber->isHit() ||
            $previousVideoNumber->get() !== $numberOfVideos->get()
        ) {

            $previousVideoNumber->set($numberOfVideos->get());
            $previousVideoNumber->expiresAfter(3600);
            $cache->save($previousVideoNumber);

            $videos->set($youtube->searchChannelVideos([], self::CHANNEL_ID, self::nbVideosPerRequest, Youtube::ORDER_DATE));
            //$videos['info'];
            $videos->expiresAfter(3600);
            $cache->save($videos);

            $nextPageToken = $videos->get()['info']['nextPageToken'];
            
            for ($i = 2; $i <= $numberOfPages; $i++) {

                if (!$nextPageToken) {
                    break;
                }

                $videos = $cache->getItem('youtube_videos_page_'.$numberOfPages);

                //if (!$videos->isHit()) {
                //  refreshing all the 'youtube_videos_page_x' from the cache to avoid inconsistancies if videos have been added on the youtube channel 
                $videos->set($youtube->searchAdvanced(['q' => [], 'pageToken' => $nextPageToken, 'channelId' => self::CHANNEL_ID, 'maxResults' => self::nbVideosPerRequest, 'order' => Youtube::ORDER_DATE], true));
                $nextPageToken = $videos->get()['info']['nextPageToken'];
                $videos->expiresAfter(3600);
                $cache->save($videos);
                //}
            }

        } else {
            $previousVideoNumber->expiresAfter(3600);
            $cache->save($previousVideoNumber);
            
            for ($i = 1; $i <= $numberOfPages; $i++) {
                
                $videos = $cache->getItem('youtube_videos_page_'.$numberOfPages);
                $videos->expiresAfter(3600);
                $cache->save($videos);

            }
        }

        $youtubeVideosPageIndex = ceil(($page * self::nbVideosPerPage) / self::nbVideosPerRequest);
        $offset = (($page - 1) * self::nbVideosPerPage) % self::nbVideosPerRequest;

        $videos = $cache->getItem('youtube_videos_page_'.$youtubeVideosPageIndex);

        //$videos = $youtube->searchChannelVideos([], self::CHANNEL_ID, $offset + self::nbVideosPerRequest, Youtube::ORDER_DATE);
        
        $videos = array_slice($videos->get()['results'], $offset, self::nbVideosPerPage);

        //$nbVideos = count($videos);

        return $this->render('youtube/index.html.twig', [
            'videos' => $videos,
            'page' => $page,
            'nbVideosPerPage' => self::nbVideosPerPage,
            'nbOfPages' => ceil($numberOfVideos->get() / self::nbVideosPerPage),
            'nbOfVideos' => $numberOfVideos->get(),
        ]);
    }
}
