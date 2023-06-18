<?php

namespace Modules\Shows;

use Datainterface\Insertion;
use Datainterface\Selection;
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

}