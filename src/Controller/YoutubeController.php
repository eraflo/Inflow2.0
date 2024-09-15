<?php

namespace App\Controller;

use Error;
use Google_Client;
use Madcoda\Youtube;
use Psr\Cache\CacheItemInterface;
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
    private $cache;
    private $youtube;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
        $this->youtube = new Youtube(['key' => self::KEY]);
    }

    #[Route('/videos/youtube/{page}', name: 'app_youtube', requirements: ['page' => '\d+'])]
    public function index($page = 1): Response
    {
        // $videos[filter = {'lastest' | 'popular'}][page][results = {'videos' | 'pageTokens'}]
        // $videos[filter = {'lastest' | 'popular'}][page]['videos'}][number]
        // $videos[filter = {'lastest' | 'popular'}][page]['pageTokens'][pageToken = {'prevPageToken' | 'nextPageToken'}]
        $videos = [];
        $latest = false;

        $channelInfo = $this->cache->get('channel_info', function (ItemInterface $item) {
            $channelInfo = $this->youtube->getChannelById(self::CHANNEL_ID);
            $item->expiresAfter(900);
            return ($channelInfo);
        });

        $numberOfVideos = (int) $channelInfo->statistics->videoCount;
        $numberOfPages = ceil($numberOfVideos / self::nbVideosPerPage);

        if ($page > $numberOfPages) {
            throw new Error('Invalid page number!');
        }

        //$videosInfo = $youtube->getPlaylistItemsByPlaylistId($channelInfo->contentDetails->relatedPlaylists->uploads);
        $params = array(
            'playlistId' => $channelInfo->contentDetails->relatedPlaylists->uploads,
            'part' => 'id, snippet, contentDetails, status',
            'maxResults' => self::nbVideosPerRequest
        );

        $videos['latest'][0] = self::getLatestVideosPerPage(1, $params);

        // Caching the last video if none is cached and the first video page hasn't just been retrieved
        $lastVideo = !$latest ? $this->cache->get('last_video', function (CacheItemInterface $item) {
            $item->expiresAfter(900);
            return ($this->youtube->searchChannelVideos([], self::CHANNEL_ID, 1, Youtube::ORDER_DATE)['results'][0]);
        }) : null;

        /* $activities = $this->youtube->getActivitiesByChannelId(self::CHANNEL_ID);
        foreach ($activities as $activity) {
            if (!isset($activity->contentDetails->upload)) {
                continue;
            }

            $publishedAt = new \DateTime($activity->snippet->publishedAt);
            $currentDate = new \DateTime('now', new \DateTimeZone('UTC'));

            if ($activity->snippet->publishedAt) {
                continue;
            }
        } */

        // Check if the cache needs to be reloaded
        if (isset($lastVideo) && $videos['latest'][0]['videos'][0]->id !== $lastVideo->id->videoId) {

            //$videos->set($youtube->searchAdvanced(['q' => [], 'pageToken' => $nextPageToken, 'channelId' => self::CHANNEL_ID, 'maxResults' => self::nbVideosPerRequest, 'order' => Youtube::ORDER_DATE], true));
            for ($i = 1; $i <= $numberOfPages; $i++) {
                $this->cache->delete('youtube.videos.' . $i);
            }
        }

        // Index of the page in the cache
        $start = ($page - 1) * self::nbVideosPerPage;
        $cachePageIndex = floor($start / self::nbVideosPerRequest) + 1;
        $offset = $start % self::nbVideosPerRequest;

        $videos['latest'][$cachePageIndex] = self::getLatestVideosPerPage($cachePageIndex, $params);

        //dd(array_slice($videos['latest'][$cachePageIndex]['videos'], $offset, self::nbVideosPerPage));

        // Get the videos in the x page
        //$videosToDisplay = array_slice($videos['latest'][$cachePageIndex]['videos'], $offset, self::nbVideosPerPage);
        $videosToDisplay = [];

        $videosToDisplay = array_merge($videosToDisplay, array_map(function ($video) {
            $video->snippet->title = html_entity_decode($video->snippet->title);
            return $video;
        }, array_slice($videos['latest'][$cachePageIndex]['videos'], $offset, self::nbVideosPerPage)));

        $numberOfVideosLeftToDisplay = self::nbVideosPerPage - (self::nbVideosPerRequest - $offset);

        while ($numberOfVideosLeftToDisplay > 0) {

            $cachePageIndex += 1;
            $videos['latest'][$cachePageIndex] = self::getLatestVideosPerPage($cachePageIndex, $params);

            $videosToDisplay = array_merge($videosToDisplay, array_map(function ($video) {
                $video->snippet->title = html_entity_decode($video->snippet->title);
                return $video;
            }, array_slice($videos['latest'][$cachePageIndex]['videos'], 0, $numberOfVideosLeftToDisplay)));

            $numberOfVideosLeftToDisplay -= self::nbVideosPerRequest;
        }

        //dd($videosToDisplay);

        /* $videos = array_map(function ($video) {
            $video->snippet->title = html_entity_decode($video->snippet->title);
            return $video;
        }, $videos); */

        return $this->render('youtube/index.html.twig', [
            'videos' => $videosToDisplay,
            'page' => $page,
            'nbVideosPerPage' => self::nbVideosPerPage,
            'nbOfPages' => $numberOfPages,
            'nbOfVideos' => $numberOfVideos,
        ]);
    }

    public function getLatestVideosPerPage($page, $params)
    {
        return $this->cache->get('youtube.videos.' . $page, function (CacheItemInterface $item) use ($page, $params) {
            if ($page > 1) {
                $prevPage = self::getLatestVideosPerPage($page - 1, $params);
                $pageToken = $prevPage['pageTokens']['nextPageToken'];
                $params['pageToken'] = $pageToken;
            } else {
                unset($params['pageToken']);
            }

            $result = $this->youtube->getPlaylistItemsByPlaylistIdAdvanced($params, true);
            $videosInfo = $result['results'];

            $videoIds = array_map(function ($item) {
                return $item->snippet->resourceId->videoId;
            }, $videosInfo);

            $item->expiresAfter(3600);

            return [
                'videos' => array_map(
                    function ($item) {
                        return $item;
                    },
                    $this->youtube->getVideosInfo($videoIds)
                ),
                'pageTokens' => [
                    'prevPageToken' => $result['info']['prevPageToken'],
                    'nextPageToken' => $result['info']['nextPageToken']
                ]
            ];
        });
    }

    public static function GetBestVideoInCache()
    {
        $cache = new FilesystemAdapter();
        $youtube = new Youtube(['key' => self::KEY]);

        # Check if not already has cached the 10 videos with the highest number of views
        $videos = $cache->getItem('youtube_best_videos');
        if (!$videos->isHit()) {
            $videos->set($youtube->searchChannelVideos([], self::CHANNEL_ID, 10, Youtube::ORDER_VIEWCOUNT), true);
            $videos->expiresAfter(3600 * 24);
            $cache->save($videos);
        }

        return $videos;
    }
}
