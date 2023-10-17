<?php

namespace groups;

use Datainterface\Query;

class GroupEpisodes
{
    public function latestMoviesUploaded(): array
    {
        $episodes = Query::query("SELECT title AS name, episode_id AS id, epso_image AS image, 
        air_date AS date, publish AS active, duration AS time FROM episodes ORDER BY created DESC");

        if(!empty($episodes))
        {
            return array_values($episodes);
        }
        return array();
    }

    public function searchByName(string $name): array
    {
        $movies = Query::query("SELECT title AS name, episode_id AS id, epso_image AS image, 
        air_date AS date, publish AS active, duration AS time FROM episodes WHERE title LIKE '%$name%' ORDER BY created DESC");

        if(!empty($movies))
        {
            return array_values($movies);
        }
        return array();
    }

    public function searchByID(int $id): array
    {
        $movies = Query::query("SELECT title AS name, episode_id AS id, epso_image AS image, 
        air_date AS date, publish AS active, duration AS time FROM episodes WHERE episode_id = :id ORDER BY created DESC", ['id'=>$id]);

        if(!empty($movies))
        {
            return array_values($movies);
        }
        return array();
    }

    public function searchBYNameAndID(string $name, int $id): array{
        $movies = Query::query("SELECT title AS name, episode_id AS id, epso_image AS image, 
        air_date AS date, publish AS active, duration AS time FROM episodes WHERE title = :name AND episode_id = :id ORDER BY created DESC", ['id'=>$id, "name"=>$name]);

        if(!empty($movies))
        {
            return array_values($movies);
        }
        return array();
    }

    public function loadEpisodesByShowID(string $showID)
    {
        $collection = [];
        $season = Query::query("SELECT season_id, season_name FROM seasons WHERE show_id = :id", ['id'=>$showID]);
        if(!empty($season))
        {
            foreach ($season as $key=>$value)
            {
                $collection[] = [
                    'sid'=>$value['season_id'],
                    'sname'=>$value['season_name'],
                    'episodes'=>array_values(
                        Query::query("SELECT title, url, epso_image AS image, publish, duration, epso_description AS overview, 
                        air_date AS date, epso_number AS number, episode_id AS id FROM episodes WHERE season_id = :id",['id'=>$value['season_id']])
                    ),
                ];
            }
            return $collection;
        }
    }

}