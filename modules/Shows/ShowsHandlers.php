<?php

namespace Modules\Shows;

use Json\Json;
use Datainterface\Query;
use Datainterface\Delete;
use Datainterface\Updating;
use Datainterface\Insertion;
use Datainterface\Selection;
use GlobalsFunctions\Globals;
use Modules\Episodes\Episode;
use Modules\NewAlerts\SubcriberNews;
use Modules\Renders\SEOTags;
use Modules\Search\Search;
use function functions\config;
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
       $query = "SELECT * FROM {$table} ORDER BY show_changed DESC";
       return Query::query($query);
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

       $uuid = "";
       $title = "";
       $desc = "";
       $img = "";

       $data = [];
       if(!empty(Globals::post('title')) && !empty(Globals::post('description')) && !empty(Globals::post('release-date'))){
           $data['title'] = Globals::post('title');
           $data['description'] = Globals::post('description');
           $data['release_date'] = Globals::post('release-date');
           $data['show_uuid'] = Json::uuid();
           $uuid = $data['show_uuid'];
           $title = $data['title'];
           $desc = $data['description'];

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
               $data['season_uuid'] = Json::uuid();
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
               $data['episode_uuid'] = Json::uuid();
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

       $newMessage = "<p>Hello Our subscriber Stream studios has upload show which you can watch on our site</p>";
       $newMessage .="<p>Show titled: {$title}<br>
                          Show summary: {$desc}</p>";
       $newMessage .= "<img src='{$img}' style='width: 20rem;'><br><br>";
       $home =Globals::protocal().'://'. Globals::serverHost().'view-tv-show?show='.$uuid;
       $newMessage .= "<a href='$home' style='width: fit-content; padding: 5px; background-color: orange;color: black; border: 1px solid orange; border-radius: 5px;'>Click To Watch</a>";
       (new SubcriberNews('New shows'))->saveEvent($newMessage);

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
       $authToken = config('TMDB');
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
               "Authorization: $authToken",
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
        $authToken = config('TMDB');
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
               "Authorization: $authToken",
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
           $uuid = "";

           $alreadyExist = Selection::selectById('tv_shows',['title'=>$showName]);

           if(!empty($alreadyExist)){
               $showId  = $alreadyExist[0]['show_id'];
               $show['show_uuid'] = Json::uuid();
               $uuid = $show['show_uuid'];
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
                       $value['season_uuid'] = Json::uuid();
                       Updating::update('seasons', $value, ['season_id'=>$seasonIds[0]['sid']]);
                       $outPut[] = "Updated Season ({$seasonIds[0]['n']})";

                   }else{
                       $value['season_uuid'] = Json::uuid();
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
                           $v['episode_uuid'] = Json::uuid();
                           Updating::update('episodes',$v,['episode_id'=>$result[0]['episode_id']]);
                           $outPut[] = "Updated Episode ({$v['epso_number']})";
                       }else{
                           $v['episode_uuid'] = Json::uuid();
                           Insertion::insertRow('episodes', $v);
                           $outPut[] = "Added Episode ({$v['epso_number']})";

                       }
                   }
               }

           }

           if(empty($alreadyExist)){
               $show['show_uuid'] = Json::uuid();
               $showId = Insertion::insertRow('tv_shows',$show);
               $outPut[] = "Added show ({$show['title']})";
               $seasonId = [];

               //SEO this show
               $seo['title'] = $show['title'];
               $seo['description'] = $show['description'];
               $seo['image'] = $show['show_image'];
               $seo['url'] = Globals::protocal()."://".Globals::serverHost()."/view-tv-show?show=".$show['show_uuid'];
               $seo['video'] = "";

               $token = SEOTags::getToken($seo['url']);
               $seo['url'] = $token;
               $seo = SEOTags::create($seo);
               (new SEOTags($token))->data($seo)->set();

               foreach ($seasons as $key=>$value){
                   $value['show_id'] = $showId;
                   $value['season_uuid'] = Json::uuid();
                   $seasonId[] = ['id'=>Insertion::insertRow('seasons', $value), 'season_number'=>$value['season_number']];

                   //seo seasons
                   unset($seo);
                   $seo['title'] = $show['title']." - ".$value['season_name'];
                   $seo['description'] = $value['description'];
                   $seo['image'] = $value['season_image'];
                   $seo['url'] = Globals::protocal()."://".Globals::serverHost()."/season?se=".$value['season_uuid'];
                   $seo['video'] = "";

                   $token = SEOTags::getToken($seo['url']);
                   $seo['url'] = $token;
                   $seo = SEOTags::create($seo);
                   (new SEOTags($token))->data($seo)->set();

                   $outPut[] = "Added Season ({$value['season_number']})";
               }

               foreach ($seasonId as $key=>$v){
                   if(isset($v['season_number'])){
                       $epis = $episodes[$v['season_number']] ?? [];
                       for($i = 0; $i < count($epis); $i++){
                           $esp = $epis[$i];
                           $esp['season_id'] = $v['id'];
                           $esp['episode_uuid'] = Json::uuid();
                           Insertion::insertRow('episodes',$esp);

                           //seo episode
                           unset($seo);
                           $seo['title'] = $show['title']." - ".$esp['title'];
                           $seo['description'] = $esp['epso_description'];
                           $seo['image'] = $esp['epso_image'];
                           $seo['url'] = Globals::protocal()."://".Globals::serverHost()."/watch?w=". $esp['episode_uuid'];
                           $seo['video'] = "";

                           $token = SEOTags::getToken($seo['url']);
                           $seo['url'] = $token;
                           $seo = SEOTags::create($seo);
                           (new SEOTags($token))->data($seo)->set();

                           $outPut[] = "Added Episode ({$esp['epso_number']})";
                       }
                   }
               }
           }

           $newMessage = "<p>Hello Our subscriber Stream studios has uploaded new Show which you can watch on our site</p>";
           $newMessage .="<p>Show titled: {$show['title']}<br>
                          Show summary: {$show['description']}</p>";
           $newMessage .= "<img src='{$show['show_image']}' style='width: 20rem;'><br><br>";
           $home =Globals::protocal().'://'. Globals::serverHost().'/view-tv-show?show='.$uuid;
           $newMessage .= "<a href='$home' style='width: fit-content; padding: 5px; background-color: orange;color: black; border: 1px solid orange; border-radius: 5px;'>Click To Watch</a>";
           (new SubcriberNews('New shows'))->saveEvent($newMessage);
       }
       return $outPut;
   }

   public static function updateEpisode($episode,$episode_id){
       $ep = Selection::selectById('episodes', ['episode_id'=>$episode_id]);
       $title = $ep[0]['title'] ?? null;
       $des = $ep[0]['epso_description'] ?? null;
       $img = $ep[0]['epso_image'] ?? null;
       $home =Globals::protocal().'://'. Globals::serverHost().'/watch?w='.$ep[0]['episode_uuid'] ?? null;
       $title .= " | ".(new Episode())->getEpisodeShowTitle($ep[0]['season_id'] ?? 0) ?? null;
       $newMessage = "<p>Hello Our subscriber Stream studios has updated Episode which you can watch on our site</p>";
       $newMessage .="<p>Episode titled: {$title}<br>
                          Episode summary: {$des}</p>";
       $newMessage .= "<img src='{$img}' style='width: 20rem;'><br><br>";
       $newMessage .= "<a href='$home' style='width: fit-content; padding: 5px; background-color: orange;color: black; border: 1px solid orange; border-radius: 5px;'>Click To Watch</a>";
       (new SubcriberNews('Episode Update'))->saveEvent($newMessage);
        SEOTags::updateSEO($home,['video'=>$episode['url']]);
        return Updating::update('episodes',$episode, ['episode_id'=>$episode_id]);
   }

    public function deleteShow(string $showId): bool
    {
        $query = "SELECT * from seasons WHERE show_id = :id";
        $resultSeason = Query::query($query, ['id'=>$showId]);

        $query = "SELECT * FROM tv_shows WHERE show_id = :id";
        $resultshow = Query::query($query, ['id'=>$showId]);


        $seasonFiles = [];
        $seasonIds = [];

        foreach ($resultSeason as $key=>$value){
            $seasonIds[] = $value['season_id'];
            $seasonFiles[] = $value['season_image'];
        }

        $esposideIds = [];
        $esposideFiles = [];

        $queryEpisodes = "SELECT * FROM episodes WHERE season_id = :id";
        foreach ($seasonIds as $key=>$value){
            $result = Query::query($queryEpisodes,['id'=>$value]);
            foreach ($result as $k=>$v){
                $esposideIds[] = $v['episode_id'];
                $esposideFiles[] = $v['epso_image'];
            }
        }

        $filesAll = $seasonFiles + $esposideFiles;
        if($resultshow){
            $filesAll[] = $resultshow[0]['show_image'];
        }

        $flag = false;

        if($this->deleteFiles($filesAll)){
            foreach ($esposideIds as $key=>$value){
                if(Delete::delete('episodes', ['episode_id'=>$value])){
                    $flag = true;
                }
            }

            foreach ($seasonIds as $key=>$value){
                if(Delete::delete('seasons',['season_id'=>$value])){
                    $flag = true;
                }
            }

            if($flag){
              return Delete::delete('tv_shows',['show_id'=>$resultshow[0]['show_id']]);
            }
        }
        return  false;
    }

    public function deleteFiles(array $files): bool
    {
        $flag = false;
        for($i = 0; $i < count($files); $i++){
            if(file_exists($files[$i])){
                unlink($files[$i]);
                $flag = true;
            }
            if(filter_var($files[$i], FILTER_VALIDATE_URL)){
                $flag = true;
            }
        }
        return $flag;
    }

    public function searchShow(string $search): array|false
    {
        $params = (new Search())->setSearching($search)->buildSearchQuery("tv");
        $query = "SELECT * FROM tv_shows ".$params;
        return Query::query($query);
    }


}
