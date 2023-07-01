<?php

namespace Modules\Imports;

use Json\Json;
use Datainterface\Query;
use Modules\Movies\Movie;
use Datainterface\Updating;
use Datainterface\Selection;
use ExcelHandler\ExcelHandler;
use Modules\StorageDefinitions\Storage;
use function Sodium\randombytes_uniform;

class ImportHandler extends Storage
{

    private array $schema;

    public function __construct()
    {
        parent::__construct();
        $this->schema = $this->schemaAll();
    }

    public function import($type, $filePath): bool
    {
        if($type === 'movies'){
            $this->moviesImpoter($filePath);
        }elseif ($type === 'shows'){
            //TODO
        }
        return false;
    }

    private function moviesImpoter($filePath):  bool
    {
        $extension = (new \SplFileInfo($filePath))->getExtension();
        if(strtolower($extension) === 'xlsx'){
            $data = ExcelHandler::getRows($filePath);

            $sortedData = [];
            $fields = $this->schema['columns'][1];
            for ($i = 1; $i < count($data); $i++){
                $value = $data[$i];
               $sortedData[] = [
                   $fields[1] => $value[0], //title
                   $fields[2] => $value[5], //url
                   $fields[3] => $value[3], //duration
                   $fields[4] => $value[1], //type
                   $fields[5] => $value[4], //release date
                   $fields[6] => $value[2], //description
               ];
            }

            //save in db
            foreach ($sortedData as $key=>$value){
                (new Movie())->addMovie($value);
            }
        }
        return false;
    }

    public static function requestMovie(int $movieId): array
    {


        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/movie/$movieId?language=en-US",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJiZDExNTI3ZDkwYmIxMWVhYTI0NGE2MzUwNTQwYWQyMSIsInN1YiI6IjY0OTZkMDg1YjM0NDA5MDBmZmViZTVlOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.VIHnt5aWjblsCAB__DD9hQEWzZblm0X5BjmtOtJBbJY",
                "accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [];
        } else {
           return json_decode($response, true);

        }
    }

    public function changeTables(): bool
    {
        $query = "ALTER TABLE tv_shows
        ADD show_uuid varchar(200)";
        Query::query($query);

        $query = "ALTER TABLE tv_shows
        ADD show_changed TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP";
        Query::query($query);

        $query = "ALTER TABLE movies
        ADD movie_uuid varchar(200)";
        Query::query($query);

        $query = "ALTER TABLE movies
        ADD movie_changed TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP";
        Query::query($query);

        $query = "ALTER TABLE seasons
        ADD season_uuid varchar(200)";
        Query::query($query);

        $query = "ALTER TABLE seasons
        ADD changed TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP";
        Query::query($query);


        $query = "ALTER TABLE episodes
        ADD episode_uuid varchar(200)";
        Query::query($query);

        $query = "ALTER TABLE episodes
        ADD changed TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP";
        Query::query($query);

        return true;
    }

    public function updateUUID()
    {
        $shows = Selection::selectAll('tv_shows');
        $movies = Selection::selectAll('movies');
        $episodes = Selection::selectAll('episodes');
        $seasons = Selection::selectAll('seasons');

        foreach($shows as $key=>$value){
            $id = $value['show_id'];
            Updating::update('tv_shows',['show_uuid'=>Json::uuid()], ['show_id'=>$id]);
        }

        foreach($movies as $key=>$value){
            $id = $value['movie_id'];
            Updating::update('movies',['movie_uuid'=>Json::uuid()], ['movie_id'=>$id]);
        }

        foreach($episodes as $key=>$value){
            $id = $value['episode_id'];
            Updating::update('episodes',['episode_uuid'=>Json::uuid()], ['episode_id'=>$id]);
        }

        foreach($seasons as $key=>$value){
            $id = $value['season_id'];
            Updating::update('seasons',['season_uuid'=>Json::uuid()], ['season_id'=>$id]);
        }

    }
}