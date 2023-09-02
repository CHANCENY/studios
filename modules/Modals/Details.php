<?php

namespace Modules\Modals;

use Datainterface\Database;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Query;
use Datainterface\Selection;
use Datainterface\Tables;
use Json\Json;
use Modules\Imports\Additionals;
use Modules\Imports\ImportHandler;
use Modules\Renders\ImageHandler;
use Modules\Shows\ShowsHandlers;
use function functions\config;

class Details
{
    private array|false $entity;
    private string $playID;

    /**
     * @param $entityID show uuid or movie uuid only
     */
    public function __construct(private readonly string $entityID)
    {
        if(is_numeric($this->entityID)){
            throw new \InvalidArgumentException("entity ID pass is numerical UUID is required");
        }
    }

    public function load(string $bundle): Details
    {
        if(empty($bundle)){
            throw new \InvalidArgumentException("bundle is empty");
        }

        $validArgument = ["movies", "shows"];
        if(!in_array($bundle, $validArgument)){
            throw new BundleInvalidException();
        }

        $table = $bundle === "shows" ? "tv_shows" : "movies";
        $primary = $bundle === "shows" ? "show_id" : "movie_id";
        $uuidColumn = $bundle === "shows" ? "show_uuid" : "movie_uuid";

        $params = "";

        if($bundle === "shows"){
            $params = "m.show_uuid AS uuid, m.title AS title, m.release_date AS release_date, 
                  m.show_image AS image, m.show_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle, a.trailer_videos AS trailers, a.tm_id AS tmID";
        }

        if($bundle === "movies"){
            $params = "m.movie_uuid AS uuid, m.title AS title, m.release_date AS release_date, m.duration AS duration, 
                  m.movie_image AS image, m.movie_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle, a.trailer_videos AS trailers, a.tm_id AS tmID";
        }

        $query = "SELECT $params FROM $table AS m LEFT JOIN additional_information AS a ON a.internal_id = m.$primary 
                   WHERE bundle = '$bundle' AND $uuidColumn = '$this->entityID'";

        $this->entity = Query::query($query);

        return $this;
    }

    public function getDuration(): string
    {
        return $this->entity[0]['duration'] ?? "";
    }

    public function getTitle(): string|null
    {
        return $this->entity[0]['title'] ?? null;
    }

    public function id(): int| null
    {
        return $this->entity[0]['id'] ?? null;
    }

    public function getGenresRenderble(): array
    {
        return Home::buildGenre($this->entity[0]['genre'] ?? "", $this->getBundle());
    }


    public function getGenre(): string
    {
        return $this->entity[0]['genre'] ?? "";
    }

    public function getReleaseDate(): \DateTime|null
    {
        print_r($this->entity[0]['release_date']);
        if(!empty($this->entity[0]['release_date'])){
            return (new \DateTime($this->entity[0]['release_date']));
        }
        return null;
    }

    public function getRating(): float
    {
        return number_format($this->entity[0]['rating'], 2);
    }

    public function getVotes(): int
    {
        return $this->entity[0]['vote'] ?? 0;
    }

    public function getPopularity(): float
    {
        return $this->entity[0]['popularity'] ?? 0.0;
    }

    public function getLanguage(): array|string
    {
        if(!str_contains($this->entity[0]['lang'], '|')){
            return $this->entity[0]['lang'] ?? "";
        }

        return explode('|', $this->entity[0]['lang']) ?? [];
    }

    public function getCountry(): array|string
    {
        if(!str_contains($this->entity[0]['countries'], "|")){
            return $this->entity[0]['countries'];
        }

        return explode("|", $this->entity[0]['countries']) ?? [];
    }

    public function entity(): array
    {
        return $this->entity[0] ?? [];
    }

    public function getImage(): string
    {
        return $this->entity[0]['image'] ?? "";
    }

    public function getOverview(): string
    {
        return $this->entity[0]['overview'] ?? "";
    }

    public function getVideoTrailers(): array
    {
        return explode(",", $this->entity[0]['trailers']) ?? [];
    }

    public function tmID(): int
    {
        return $this->entity[0]['tmID'] ?? 0;
    }


    public function getBundle(): string
    {
        return $this->entity[0]['bundle'] ?? "";
    }

    public function getMorePhotos(): array
    {
        return (new ImageHandler(""))->moreImages($this);
    }


    public function countryRenderable(): array
    {
        return Home::buildCountryLink($this->entity[0]['countries'] ?? "", $this->getBundle());
    }

    public function reviews(): array
    {
        return (new Additionals(""))->reviews($this);
    }

    public function getYouMayLike(): array|false
    {
        $genre = $this->getGenre();
        $bundle = $this->getBundle();
        $table = $bundle === "shows" ? "tv_shows" : "movies";
        $primary = $bundle === "shows" ? "show_id" : "movie_id";
        $uuidColumn = $bundle === "shows" ? "show_uuid" : "movie_uuid";

        if($bundle === "shows"){
            $params = "m.show_uuid AS uuid, m.title AS title, m.release_date AS release_date, 
                  m.show_image AS image, m.show_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle, a.trailer_videos AS trailers, a.tm_id AS tmID";
        }

        if($bundle === "movies"){
            $params = "m.movie_uuid AS uuid, m.title AS title, m.release_date AS release_date, m.duration AS duration, 
                  m.movie_image AS image, m.movie_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle, a.trailer_videos AS trailers, a.tm_id AS tmID";
        }

        $query = "SELECT $params FROM $table AS m LEFT JOIN additional_information AS a ON a.internal_id = m.$primary 
                   WHERE bundle = '$bundle' AND $uuidColumn != '$this->entityID' AND genres LIKE '%$genre%' ORDER BY rand() LIMIT 10";

        return Query::query($query);
    }


    public function getShowsInfo($show_id, $emptyIgnore = false): array
    {
        $showInfo = (new ShowsHandlers())->getSeasons($show_id, $emptyIgnore);

        $collection = [];
        foreach ($showInfo as $key=>$value){
            $temp = $value;
            $episodes = (new ShowsHandlers())->getEpisodes($value['season_id'], $emptyIgnore);
            $temp['episodes_found'] = $episodes;
            $collection[] = $temp;
        }

        return $collection;
    }

    public function getTrailers(): string
    {
        return $this->entity[0]['trailers'] ?? "";
    }

    public function remoteInfo(string $string, $id)
    {

        if(Tables::tablesExists(['expected_premiers'])){
            $this->entity = Selection::selectById("expected_premiers", ['tmID'=>$id]);
            if(!empty($this->entity)){
                return $this;
            }
        }
        if($string === "movies"){
            $authToken = \functions\config('TMDB');
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.themoviedb.org/3/movie/$id?language=en-US",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: $authToken",
                    "accept: application/json"
                ],
            ]);
            $response =json_decode( curl_exec($curl), true);
            $err = curl_error($curl);
            curl_close($curl);

           $this->entity['overview'] = $response['overview'] ?? null;
           $this->entity['title'] = $response['title'] ?? $response['original_title'] ?? null;
           $this->entity['release_date'] = $response['release_date'] ?? date('dY-m-d');
           $this->entity['duration'] = $response['runtime'] ?? 0;
           $this->entity['rating'] = $response['vote_average'] ?? 0.0;
           $this->entity['vote'] = $response['vote_count'] ?? 0;
           $this->entity['popularity'] = $response['popularity'] ?? null;
           $this->entity['image'] ="https://image.tmdb.org/t/p/w500". $response['poster_path'] ?? $response['backdrop_path'];
           $this->entity['lang'] = $response['original_language'] ?? null;

           $countries = $response['production_countries'] ?? [];
           $list = [];
           foreach ($countries as $key=>$value){
              $list[] = $value['iso_3166_1'] ?? "";
           }
           $this->entity['countries'] = implode(',', $list);

           $genres = $response['genres'] ?? [];
           $list = [];
           foreach ($genres as $key=>$value){
               $list[] = $value['name'] ?? "";
           }
           $this->entity['genre'] = implode('|', $list);
           $this->entity['tmID'] = $response['id'] ?? null;


            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.themoviedb.org/3/movie/$id/videos?language=en-US",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: $authToken",
                    "accept: application/json"
                ],
            ]);

            $responses = json_decode(curl_exec($curl), true);
            $err = curl_error($curl);
            curl_close($curl);

            $trailers = [];
            if(!empty($responses['results'])){
                foreach ($responses['results'] as $key=>$value){
                    if(gettype($value) === 'array'){
                        if(isset($value['type']) &&
                            isset($value['official']) &&
                            isset($value['site']) &&
                            isset($value['key']) &&
                            $value['type'] === "Trailer" &&
                            $value['official'] === true &&
                            $value['site'] === "YouTube" &&
                            !empty($value['key'])){
                          $trailers[] = "https://www.youtube.com/watch?v={$value['key']}";
                        }
                    }
                }
            }
            $this->entity['trailers'] = implode(',',$trailers);
            $columns = array_keys($this->entity);
            $attributes = [];

            foreach ($columns as $key=>$value){
              $attributes[$value]=['varchar(300)', 'null'];
            }

            (new MysqlDynamicTables())->resolver(Database::database(), $columns, $attributes, "expected_premiers", true);
            Insertion::insertRow("expected_premiers", $this->entity);
            $this->entity = [];
            $this->remoteInfo($string, $id);
            return $this;
        }
    }


}