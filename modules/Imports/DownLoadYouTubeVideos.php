<?php

namespace Modules\Imports;
use Modules\Modals\Debug;


class DownLoadYouTubeVideos
{

    /**
     * @param array $youtube id in additional table, trailers column data
     */
    public function __construct(private readonly array $youtube)
    {
    }

    public function download(): bool
    {
        $list = explode(',', $this->youtube['trailer_videos']);
        $streamingLinks = [];
        foreach ($list as $key=>$value){
            $l = explode('=', $value);
            $streamingLinks[] = $this->findVideo(end($l));
        }

        return false;
    }

    private function findVideo($id): string
    {
        $api = "AIzaSyC0lPZ0ZDKJRQd7DVCfRAxSYMJSgN34stI";
        $yt = new YoutubeDownloader();
        $registrationResponse = $yt->registerDevice();
        $playerResponse = $yt->playerRequest->fetch_player_info($id);
        $videos = $playerResponse->getPlayerVideos()->getVideos();
        Debug::debug($videos->getVideoUrl());
        exit;
        return "";
    }

}