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


        // Caching number of videos if none are cached
        $numberOfVideos = $cache->getItem('number_of_videos');
        if (!$numberOfVideos->isHit()) {
            $numberOfVideos->set((int) $youtube->getChannelById(self::CHANNEL_ID)->statistics->videoCount);
            $numberOfVideos->expiresAfter(1800);
            $cache->save($numberOfVideos);
        }

        //$cache->deleteItem('last_video');

        // Caching last video if none is cached
        $lastVideo = $cache->getItem('last_video');
        if (!$lastVideo->isHit()) {
            $lastVideo->set($youtube->searchChannelVideos([], self::CHANNEL_ID, 1, Youtube::ORDER_DATE));
            $lastVideo->expiresAfter(1800);
            $cache->save($lastVideo);
        }


        //  only the number of pages / resultset returned by the api
        $numberOfPages = ceil($numberOfVideos->get() / self::nbVideosPerRequest);

        // Get the videos from page 1 from the cache if they are already cached
        $videos = $cache->getItem('youtube_videos_page_1');
        $previousVideoNumber = $cache->getItem('previous_video_number');

        // Check if the last video has changed
        if (
            !$videos->isHit() ||
            $cache->getItem('youtube_videos_page_1')->get()['results'][0]->id->videoId !== $lastVideo->get()['results'][0]->id->videoId ||
            !$previousVideoNumber->isHit() ||
            $previousVideoNumber->get() !== $numberOfVideos->get()
        ) {
            // if the last video has changed, we need to refresh the cache
            $previousVideoNumber->set($numberOfVideos->get());
            $previousVideoNumber->expiresAfter(3600);
            $cache->save($previousVideoNumber);

            // refreshing all the 'youtube_videos_page_1' from the cache to avoid inconsistancies if videos have been added on the youtube channel
            $videos->set($youtube->searchChannelVideos([], self::CHANNEL_ID, self::nbVideosPerRequest, Youtube::ORDER_DATE));
            $videos->expiresAfter(3600);
            $cache->save($videos);

            // if there are more than 50 videos, we need to get the other pages
            if($videos->get()['info']['nextPageToken']) {
                $nextPageToken = $videos->get()['info']['nextPageToken'];
            } else {
                $nextPageToken = null;
            }
            
            // refreshing all the 'youtube_videos_page_x' from the cache to avoid inconsistancies if videos have been added on the youtube channel
            for ($i = 2; $i <= $numberOfPages; $i++) {

                if (!$nextPageToken) {
                    break;
                }

                $videos = $cache->getItem('youtube_videos_page_'.$numberOfPages);

                //if (!$videos->isHit()) {
                    
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

        // Index of the page in the cache
        $youtubeVideosPageIndex = ceil(($page * self::nbVideosPerPage) / self::nbVideosPerRequest);
        $offset = (($page - 1) * self::nbVideosPerPage) % self::nbVideosPerRequest;

        $videos = $cache->getItem('youtube_videos_page_'.$youtubeVideosPageIndex);

        // Get the videos in the x page
        $videos = array_slice($videos->get()['results'], $offset, self::nbVideosPerPage);

        return $this->render('youtube/index.html.twig', [
            'videos' => $videos,
            'page' => $page,
            'nbVideosPerPage' => self::nbVideosPerPage,
            'nbOfPages' => ceil($numberOfVideos->get() / self::nbVideosPerPage),
            'nbOfVideos' => $numberOfVideos->get(),
        ]);
    }
}
