<?php

namespace Modules\Shows;

use Datainterface\Insertion;
use Datainterface\Query;
use Datainterface\Selection;
use Datainterface\Updating;
use GlobalsFunctions\Globals;
use Modules\StorageDefinitions\Storage;

class ShowsHandlers extends Storage
{
    /**
     * @var array 'tv_shows', 'movies', 'related', 'seasons', 'episodes','genres'
     */
    private array $schema;
    private string $message;

    public function __construct()
    {
        parent::__construct();
        $this->schema = $this->schemaAll();
    }

    public function shows(): array
   {
       $table = $this->schema['tables'][0];
       return Selection::selectAll($table);
   }

   public function showById($show_id): array
   {
       return Selection::selectById($this->schema['tables'][0],['show_id'=>$show_id])[0] ?? [];
   }

   public function addShow(): array
   {
       $tables = $this->schema['tables'];

       $flagError = false;
       $this->message = "<ul>";

       $showId = 0;
       $seasonId = 0;
       $episodeId = 0;
       $relatedId = 0;

       $data = [];
       if(!empty(Globals::post('title')) && !empty(Globals::post('description')) && !empty(Globals::post('release-date'))){
           $data['title'] = Globals::post('title');
           $data['description'] = Globals::post('description');
           $data['release_date'] = Globals::post('release-date');
           $showId = Insertion::insertRow($tables[0],$data);
       }else{
           if(!empty(Globals::post('available'))){
               $showId = Globals::post('available');
           }else{
               $flagError = true;
               $this->message .= "<li>Show title no provided</li>";
           }
       }

       if(!$flagError){

           /**
            * Seasons
            */
           unset($data);
           if(!empty(Globals::post('season'))){
               $data['season_name'] = Globals::post('season');
               $data['show_id'] = $showId;
               $seasonId = Insertion::insertRow($tables[3], $data);
           }else{
               if(!empty(Globals::post('season-available'))){
                   $seasonId = Globals::post('season-available');
               }else{
                   $flagError = true;
                   $this->message .= "<li>Season name not provided</li>";
               }
           }
       }

       if(!$flagError){
           /**
            * Episodes
            */
           unset($data);
           if(!empty(Globals::post('episode'))){
               $data['title'] = Globals::post('episode');
           }else{
               $flagError = true;
               $this->message .= "<li>Episode name not provided</li>";
           }

           if(!empty(Globals::post('episodeurl'))){
               $data['url'] = Globals::post('episodeurl');
           }else{
               $flagError = true;
               $this->message .= "<li>Episode Url not provided</li>";
           }

           if(!empty(Globals::post('duration'))){
               $data['duration'] = Globals::post('duration');
           }else{
               $flagError = true;
               $this->message .= "<li>Episode duration not provided</li>";
           }

           if(!empty(Globals::post('type'))){
               $data['type'] = $this->createGenres(Globals::post('type'));
           }else{
               $flagError = true;
               $this->message .= "<li>Episode type not provided</li>";
           }

           if(!$flagError){
               $data['season_id'] = $seasonId;
               $episodeId = Insertion::insertRow($tables[4], $data);
           }
       }

       if(!$flagError){
           /**
            * Related shows
            */
           if(!empty(Globals::post('shows-related'))){
               unset($data);
               $data['show_id'] = implode(',',$_POST['shows-related']);
               $relatedId = Insertion::insertRow($tables[2], $data);
           }else{
               $this->message .= "<li>Related shows not provided</li>";
           }
       }

       return [
           'show'=>$showId,
           'season'=>$seasonId,
           'episode'=>$episodeId,
           'related'=>$relatedId,
           'error'=>$flagError,
           'message'=> $this->message .= "</ul>"
       ];
   }

   public function createGenres(string $genre): int
   {
       $result = Selection::selectById($this->schema['tables'][5],['genre_name'=>$genre]);
       if(empty($result)){
           return Insertion::insertRow($this->schema['tables'][5],['genre_name'=>$genre]);
       }else{
           return $result[0]['genre_id'] ?? 0;
       }
   }

   public function getSeason($show_id):array
   {
       return Selection::selectById($this->schema['tables'][3], ['show_id'=>$show_id]) ?? [];
   }

   public function getEpisodes(int $season_id): array
   {
       return Selection::selectById($this->schema['tables'][4],['season_id'=>$season_id]) ?? [];
   }

   public function getEpisode(int $episode_id): array
   {
       return Selection::selectById($this->schema['tables'][4],['episode_id'=>$episode_id]) ?? [];
   }

   public function getSeasons(int $show_id): array
   {
       return Selection::selectById($this->schema['tables'][3],['show_id'=>$show_id]) ?? [];
   }

   public function listingShow($show_id): array
   {
       $seasons = $this->getSeasons($show_id);
       $fullShow = [];
       foreach ($seasons as $key=>$value){
           $sid = $value['season_id'];
           $episodes = $this->getEpisodes($sid);
           $fullShow[$value['season_name']] = $episodes;
       }
       return $fullShow;
   }

   public function getGenre($type): string
   {
       return Selection::selectById($this->schema['tables'][5], ['genre_id'=>$type])[0]['genre_name'] ?? "";
   }

   public static function getShowTmDB($showId): array
   {


       $curl = curl_init();

       curl_setopt_array($curl, [
           CURLOPT_URL => "https://api.themoviedb.org/3/tv/$showId?language=en-US",
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
       return [
           'error'=>$err,
           'data'=>json_decode($response, true)
       ];
   }


   public static function getAllEpisodesTmDB($show, $season): array{


       $curl = curl_init();

       curl_setopt_array($curl, [
           CURLOPT_URL => "https://api.themoviedb.org/3/tv/$show/season/$season?language=en-US",
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
       return [
           'error'=>$err,
           'data'=>json_decode($response, true)
       ];
   }


   public static function saveTmDBShow($show, $seasons, $episodes): array
   {
       $outPut = [];
       if(!empty($show) && !empty($seasons) && !empty($episodes)){

           $showName = $show['title'];

           $alreadyExist = Selection::selectById('tv_shows',['title'=>$showName]);

           if(!empty($alreadyExist)){
               $showId  = $alreadyExist[0]['show_id'];

               Updating::update('tv_shows', $show, ['show_id'=>$showId]);
               $newSeason = [];
               $outPut[] = "Updated Show ({$show['title']})";

               foreach ($seasons as $key=>$value){
                   $query = "SELECT tv.show_id AS tid, se.season_id AS sid, 
                             se.season_number AS n FROM tv_shows AS tv LEFT JOIN seasons AS
                                 se ON se.season_id  = tv.show_id WHERE tv.show_id = $showId AND se.season_number = {$value['season_number']}";
                   $seasonIds = Query::query($query);
                   if(!empty($seasonIds)){
                       $newSeason[] =['id'=>$seasonIds[0]['sid'], 'n'=>$seasonIds[0]['n']];
                       Updating::update('seasons', $value, ['season_id'=>$seasonIds[0]['sid']]);
                       $outPut[] = "Updated Season ({$seasonIds[0]['n']})";
                   }else{
                      $newSeason[] =['id'=>Insertion::insertRow('seasons', $value),'n'=>$value['season_number']];
                       $outPut[] = "Added Season ({$value['season_number']})";
                   }
               }

               foreach ($newSeason as $key=>$value){
                   $incomingEp = $episodes[$value['n']] ?? [];
                   foreach ($incomingEp as $key=>$v){
                       $query = "SELECT * FROM episodes WHERE season_id = {$value['id']} AND epso_number = {$v['epso_number']}";
                       $result = Query::query($query);
                       if(!empty($result)){
                           Updating::update('episodes',$v,['episode_id'=>$result[0]['episode_id']]);
                           $outPut[] = "Updated Episode ({$v['epso_number']})";
                       }else{
                           Insertion::insertRow('episodes', $v);
                           $outPut[] = "Added Episode ({$v['epso_number']})";
                       }
                   }
               }

           }

           if(empty($alreadyExist)){
               $showId = Insertion::insertRow('tv_shows',$show);
               $outPut[] = "Added show ({$show['title']})";
               $seasonId = [];

               foreach ($seasons as $key=>$value){
                   $value['show_id'] = $showId;
                   $seasonId[] = ['id'=>Insertion::insertRow('seasons', $value), 'season_number'=>$value['season_number']];
                   $outPut[] = "Added Season ({$value['season_number']})";
               }

               foreach ($seasonId as $key=>$v){
                   $epis = $episodes[$v['season_number']];
                   for($i = 0; $i < count($epis); $i++){
                       $esp = $epis[$i];
                       $esp['season_id'] = $v['id'];
                       Insertion::insertRow('episodes',$esp);
                       $outPut[] = "Added Episode ({$esp['epso_number']})";
                   }
               }
           }
       }
       return $outPut;
   }

   public static function updateEpisode($episode,$episode_id){
        return Updating::update('episodes',$episode, ['episode_id'=>$episode_id]);
   }


}
