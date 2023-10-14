<?php

namespace groups;

use Datainterface\Delete;
use Datainterface\Query;
use Datainterface\Selection;
use Modules\Imports\Additionals;
use Modules\Renders\ImageHandler;
use Modules\Shows\ShowsHandlers;

class GroupShows
{
    /**
     * @var array|mixed
     */
    private mixed $show;

    public function __call(string $name, array $arguments)
    {
        return match ($name) {
            'title' => $this->show['title'] ?? null,
            'date' => $this->show['release_date'] ?? (new \DateTime("now"))->format("d/m/y"),
            'overview' => $this->show['description'] ?? null,
            'image' => $this->show['show_image'] ?? null,
            default => $this->show,
        };
    }

    public function showsListings(): array
    {
        $query = "SELECT tv.show_id AS id, tv.title,  tv.release_date AS date,  tv.show_image AS image, CASE  WHEN ai.additional_id IS NOT NULL THEN 1  ELSE 0 END AS active FROM tv_shows AS tv
         LEFT JOIN additional_information AS ai ON tv.show_id = ai.internal_id WHERE ai.bundle = 'shows' OR ai.bundle IS NULL ORDER BY tv.created DESC";

        $data = Query::query($query);
        if(!empty($data))
        {
            return array_values($data);
        }
        return array();
    }

    public function searchByName(string $name): array
    {

        $query = "SELECT
    tv.show_id AS id,
    tv.title,
    tv.release_date AS date,
    tv.show_image AS image,
    CASE
        WHEN ai.additional_id IS NOT NULL THEN 1
        ELSE 0
    END AS active
FROM
    tv_shows AS tv
LEFT JOIN
    additional_information AS ai
ON
    tv.show_id = ai.internal_id
WHERE
    (ai.bundle = 'shows' OR ai.bundle IS NULL)
    AND tv.title LIKE '%$name%'
ORDER BY
    tv.created DESC;
";

        $data = Query::query($query);
        if(!empty($data))
        {
            return array_values($data);
        }
        return array();
    }

    public function searchByID(int $id): array
    {

        $query = "SELECT
    tv.show_id AS id,
    tv.title,
    tv.release_date AS date,
    tv.show_image AS image,
    CASE
        WHEN ai.additional_id IS NOT NULL THEN 1
        ELSE 0
    END AS active
FROM
    tv_shows AS tv
LEFT JOIN
    additional_information AS ai
ON
    tv.show_id = ai.internal_id
WHERE
    (ai.bundle = 'shows' OR ai.bundle IS NULL)
    AND tv.show_id = :id
ORDER BY
    tv.created DESC;
";

        $data = Query::query($query, ['id'=>$id]);
        if(!empty($data))
        {
            return array_values($data);
        }
        return array();
    }

    public function searchBYNameAndID(string $name, int $id): array{

       $query = "SELECT
    tv.show_id AS id,
    tv.title,
    tv.release_date AS date,
    tv.show_image AS image,
    CASE
        WHEN ai.additional_id IS NOT NULL THEN 1
        ELSE 0
    END AS active
FROM
    tv_shows AS tv
LEFT JOIN
    additional_information AS ai
ON
    tv.show_id = ai.internal_id
WHERE
    (ai.bundle = 'shows' OR ai.bundle IS NULL)
    AND tv.show_id = :id
    AND tv.title = :title
ORDER BY
    tv.created DESC;
";
        $data = Query::query($query, ['id'=>$id, 'title'=>$name]);
        if(!empty($data))
        {
            return array_values($data);
        }
        return array();
    }

    public function loadForEdit(int $id): void
    {
        $data = Query::query("SELECT * FROM tv_shows WHERE show_id = :id", ['id'=>$id]);
        if(!empty($data))
        {
            $this->show = $data[0];
        }
        else{
            $this->show = [];
        }
    }

    public function saveShows($showID): bool
    {
        $showDetails = \Modules\Shows\ShowsHandlers::getShowTmDB($showID);
        $newInternal = $this->expectedRowID();
        $data = [];
        if(empty($showDetails['error'])){
            $m = $showDetails['data'];
            $data['title'] = $m['name'] ?? $m['original_name'];
            $data['description'] = $m['overview'] ?? null;
            $data['release_date'] = $m['first_air_date'] ?? null;
            $data['show_image'] = $this->generateImage($m['poster_path'] ?? $m['backdrop_path']);
            $genreType = implode(',', $this->map($result['genres'] ?? [], 'name'));
            $seasons = $this->makeSeasons($m['seasons']);
            $episodes = $this->episodeGather($showID,$m['number_of_seasons']);
            $result = \Modules\Shows\ShowsHandlers::saveTmDBShow($data, $seasons, $episodes);
            if(!empty($result))
            {
                if($this->addOtherMore($m, $newInternal))
                {
                    return true;
                }
            }
        }

        return false;
    }
    public function map($array , $key){

        $list = [];
        foreach ($array as $k=>$value){
            if(gettype($value) == 'array'){
                $list[] = $value[$key];
            }
        }
        return$list;
    }

    public function expectedRowID(): int
    {
        $database = \Datainterface\Database::getDbname();
        $query = "SELECT AUTO_INCREMENT  FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = 'tv_shows'";
        $data = \Datainterface\Query::query($query);
        if(!empty($data))
        {
            return $data[0]["AUTO_INCREMENT"] ?? 0;
        }
        return 0;
    }

    public function makeSeasons($seasons){

        $list = [];
        foreach ($seasons as $key=>$value){
            $list[] = [
                'season_name'=>$value['name'],
                'season_image'=>$this->generateImage($value['poster_path']),
                'episode_count'=>$value['episode_count'],
                'description' => $value['overview'],
                'air_date'=> $value['air_date'],
                'season_number'=>$value['season_number']
            ];
        }
        return $list;
    }

    public function generateImage($link):string
    {
        if(empty($link)){
            return "";
        }
        return "https://image.tmdb.org/t/p/w500". $link;
    }

    public function episodeGather($show, $count){

        $list = [];
        for($i = 1; $i <= $count; $i++){
            $episodes = \Modules\Shows\ShowsHandlers::getAllEpisodesTmDB($show, $i)['data']['episodes'] ?? [];
            if(!empty($episodes)){
                foreach ($episodes as $key=>$value){
                    $list[$value['season_number']][] = [
                        'title'=>$value['name'],
                        'duration'=>$value['runtime'],
                        'epso_description'=>$value['overview'],
                        'epso_image'=>$this->generateImage($value['still_path']),
                        'epso_number'=>$value['episode_number'],
                        'air_date'=>$value['air_date'],
                        'publish'=>'no'
                    ];
                }
            }
        }
        return $list;
    }

    public function addOtherMore($show, $newShowInternal): bool
    {
        $data = [
            "internal_id"=>$newShowInternal,
            "bundle"=>"shows",
            "tm_id"=>$show['id'],
            "popularity"=> $show['popularity'],
            "vote_average"=> $show['vote_average'],
            "vote_count"=> $show['vote_count'],
            "original_language"=> $show['original_language'],
            "origin_country"=> $this->countries($show),
            "genres"=>$this->tags($show),
            "trailer_videos"=>(new Additionals())->showTrailers($show['id'])
        ];
        if((new Additionals())->saveAdditional($data))
        {
            return true;
        }
        return false;
    }

    public function tags($showDetails): string{
        $genres = $showDetails['genres'] ?? [];
        $genresLine = [];
        foreach ($genres as $key=>$value) {
            $name = $value['name'];
            $genresLine[] = $name;
        }
       return implode(" | ", $genresLine);
    }

    public function countries($showDetails): string{
        $genres = $showDetails['origin_country'] ?? [];
        $genresLine = [];
        foreach ($genres as $key=>$value) {
            $genresLine[] = $value;
        }
        return implode(",", $genresLine);
    }

    public function deleteShow($showID): bool
    {
        return (new ShowsHandlers())->deleteShow($showID);
    }

    public function deleteSeason($seasonID): bool
    {
        $data = Selection::selectById("seasons", ['season_id'=>$seasonID]);
        if(!empty($data))
        {
            $image = $data[0]['season_image'] ?? null;
            if(!empty($image))
            {
                $imageID = str_contains($image, "=") ? explode("=", $image) : null;
                $imagePath = null;
                if(!empty($imageID))
                {
                    $imagePath = (new ImageHandler(end($imageID)))->loadImage()->getPath();
                }
                if(Delete::delete("seasons",['season_id'=>$seasonID]))
                {
                    return unlink($imagePath);
                }
            }
            return Delete::delete("seasons", ['season_id'=>$seasonID]);
        }
        return false;
    }

    public function deleteEpisode($episodeID): bool
    {
        $data = Selection::selectById("episodes", ['episode_id'=>$episodeID]);
        if(!empty($data))
        {
            $image = $data[0]['epso_image'] ?? null;
            if(!empty($image))
            {
                $imageID = str_contains($image, "=") ? explode("=", $image) : null;
                $imagePath = null;
                if(!empty($imageID))
                {
                    $imagePath = (new ImageHandler(end($imageID)))->loadImage()->getPath();
                }
                if(Delete::delete("episodes",['episode_id'=>$episodeID]))
                {
                    return unlink($imagePath);
                }
            }
            return Delete::delete("episodes", ['episode_id'=>$episodeID]);
        }
        return false;
    }
}
