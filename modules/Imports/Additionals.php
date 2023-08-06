<?php

namespace Modules\Imports;

use Datainterface\Database;
use Datainterface\Insertion;
use Datainterface\mysql\UpdatingLayer;
use Datainterface\MysqlDynamicTables;
use Datainterface\Query;

class Additionals
{

    /**
     * @var array|string|string[]
     */
    private string|array $searchString;
    private array $dataFound;

    /**
     * @return array
     */
    public function getDataFound(): array
    {
        return $this->dataFound;
    }

    public function __construct(string $searchTitle = "")
    {
        $this->additionalSchema();
        $this->searchString = str_replace(' ','-',$searchTitle);
    }

    public function searchMovies(): array
    {
        $authToken = \functions\config('TMDB');
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/search/movie?query=$this->searchString&include_adult=false&language=en-US&page=1",
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

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        return [
            'error'=>$err,
            'body'=>json_decode($response, true)
        ];
    }

    public function search($type = "shows"): Additionals
    {
        $movies = $type === "shows" ? $this->searchShows() : $this->searchMovies();
        $this->dataFound = $movies['body']['results'] ?? null;
        return $this;
    }

    private function searchShows()
    {
        $authToken = \functions\config('TMDB');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/search/tv?query=$this->searchString&include_adult=false&language=en-US&page=1",
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

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        return [
            'error'=>$err,
            'body'=> json_decode($response,  true)
        ];
    }

    public function additionalSchema()
    {
        $column = [
            "additional_id",
            "internal_id",
            "bundle",
            "tm_id",
            "popularity",
            "vote_average",
            "vote_count",
            "original_language",
            "origin_country",
            "trailer_videos",
        ];

        $attributes = [
            "additional_id"=>["int(11)", "auto_increment", "primary key"],
            "internal_id" => ["int(11)", "not null"],
            "bundle" => ["varchar(200)", "not null"],
            "tm_id" => ["int(11)", "not null"],
            "popularity" => ["varchar(50)"],
            "vote_average" => ["varchar(50)"],
            "vote_count" => ["varchar(50)"],
            "original_language" => ["varchar(50)"],
            "origin_country"=> ['varchar(250)'],
            "trailer_videos" => ["text"],
        ];

        $table = "additional_information";

        (new MysqlDynamicTables())->resolver(
            Database::database(),
            $column,
            $attributes,
            $table,
            false
        );
    }

    public function saveAdditional($data): bool
    {
        return Insertion::insertRow("additional_information", $data);
    }

    public function showTrailers($show_id): string
    {
        $authToken = \functions\config('TMDB');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/tv/$show_id/videos?language=en-US",
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

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $data = json_decode($response,  true);
        $data = $data['results'] ?? [];

        $allVideos = [];
        foreach ($data as $key=>$value){

            if(isset($value['type']) &&
                $value['type'] === "Trailer" &&
                isset($value['name']) &&
                str_contains($value['name'], "Trailer") &&
                isset($value['official']) && $value['official'] === true &&
                isset($value['site']) && $value['site'] === "YouTube"
            ){
                $allVideos[] ="https://www.youtube.com/watch?v=". $value['key'];
            }
        }
        return implode(',', $allVideos);

    }

    public function movieTrailers($movie_id): string
    {
        $authToken = \functions\config('TMDB');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/movie/$movie_id/videos?language=en-US",
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

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $data = json_decode($response,  true);
        $data = $data['results'] ?? [];

        $allVideos = [];
        foreach ($data as $key=>$value){

            if(isset($value['type']) &&
                $value['type'] === "Trailer" &&
                isset($value['name']) &&
                str_contains($value['name'], "Trailer") &&
                isset($value['official']) && $value['official'] === true &&
                isset($value['site']) && $value['site'] === "YouTube"
              ){
                $allVideos[] ="https://www.youtube.com/watch?v=". $value['key'];
            }
        }
        return implode(',', $allVideos);
    }

    public function findCountry($type, $id): string
    {
        $authToken = \functions\config('TMDB');
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/$type/$id?language=en-US",
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

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $data =  json_decode($response,  true);
        if($type === "movie"){
            $countries = [];
            foreach ($data["production_countries"] as $key=>$value){
               $countries[] = $value['iso_3166_1'] ?? null;
            }
            return implode(',', $countries) ?? "";
        }
        return implode(',', array_values($data['origin_country'])) ?? "";

    }

    public function addRemainingInfo($limit = 1): bool
    {
        $query = "SELECT * FROM additional_information WHERE trailer_videos IS NULL ORDER BY additional_id LIMIT $limit";
        $data = Query::query($query);
        foreach ($data as $key=>$value){
            $toUpdate = [];
            if(isset($value['bundle']) && $value['bundle'] === 'movies'){
                if(empty($value['origin_country'])){
                    $toUpdate['origin_country'] = $this->findCountry('movie',$value['tm_id']);
                }
                $toUpdate['trailer_videos'] = $this->movieTrailers($value['tm_id']);
            }elseif(isset($value['bundle']) && $value['bundle'] === "shows"){
                if(empty($value['origin_country'])){
                    $toUpdate['origin_country'] = $this->findCountry('tv',$value['tm_id']);
                }
                $toUpdate['trailer_videos'] = $this->showTrailers($value['tm_id']);
            }
            if(!empty($toUpdate)){
                (new UpdatingLayer())->setTableName("additional_information")
                    ->setData($toUpdate)
                    ->keys(["additional_id"=>$value['additional_id']])
                    ->update();
            }

        }
        return false;
    }

}