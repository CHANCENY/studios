<?php

namespace Modules\Modals;

use Datainterface\Query;
use GlobalsFunctions\Globals;
use Json\Json;

class Playing
{
    private array $player;
    public function __construct(private readonly Details $details)
    {
    }

    private function collect(): void
    {
        $this->who['ip'] = $_SERVER['REMOTE_ADDR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
    }

    public function token(): string
    {
        $uid = "";
        $type = "";
        if($this->details->getBundle() === "movies"){
            $uid = Globals::get('movie-id');
            $type = "movies";
        }
        if($this->details->getBundle() === "shows"){
            $uid = Globals::get('series-id');
            $type = "shows";
        }
        $token = Json::uuid();
        $_SESSION['playing'][Globals::user()[0]['uid']][$token] =['id'=>$uid, 'type'=>$type];
        return "/stream-studio-playing?play=$token";
    }


    public function getMovie(string $uuid): array|false
    {
        $query = "SELECT * FROM movies WHERE movie_uuid = '$uuid'";
        return Query::query($query)[0] ?? false;
    }

    public function getEpisode(string $uuid): array|false
    {
        $query = "SELECT * FROM episodes WHERE episode_uuid = '$uuid'";
        return Query::query($query)[0] ?? false;
    }


    public function episodeLink($uuid): string
    {
        $token = Json::uuid();
        $_SESSION['playing'][Globals::user()[0]['uid']][$token] =['id'=>$uuid, 'type'=>"episode"];
        return "/stream-studio-playing?play=$token";
    }

    public function load(array $data): Playing
    {
        $this->player['title'] = $data['title'];
        $this->player['duration'] = $data['duration'];
        $this->player['description'] = $data['description'] ?? $data['epso_description'];
        $this->player['url'] = $data['url'];
        $this->player['image'] = $data['movie_image'] ?? $data['epso_image'];
        $this->player['date'] = $data['release_date'] ?? $data['air_date'];
        $this->player['episodeNumber'] = $data['epso_number'] ?? null;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->player['title'] ?? "";
    }

    public function getDate(): string
    {
         return (new \DateTime($this->player['date']))->format("M d, Y");
    }

    public function getDuration(): string
    {
        return empty($this->player['duration']) ? "0 min" : $this->player['duration'] . " min";
    }

    public function getUrl(): string
    {
        return $this->player['url'] ?? "";
    }

    public function getImage(): string
    {
        return $this->player['image'] ?? "";
    }

    public function getEpisodeNumber(): string
    {
       return $this->player['episodeNumber'];
    }

    public function overview(): string
    {
        return $this->player['description'];
    }


    public function videoID(): string
    {
        $link = $this->getUrl();
        $list = explode('/', $link);
        return $list[4] ?? "";
    }

}