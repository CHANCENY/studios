<?php

namespace Modules\Imports;

use Datainterface\Database;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Query;
use Datainterface\Updating;
use FileHandler\FileHandler;
use GlobalsFunctions\Globals;
use Json\Json;
use Modules\Renders\SEOTags;

class ImageCreation
{

    public function unProcessedImages(): array
    {
        $unProcessedList = [];
        $showQuery = "SELECT * FROM tv_shows WHERE show_image LIKE '%image.tmdb.org%' ORDER BY show_id ASC LIMIT 20";
        $seasonQuery = "SELECT * FROM seasons WHERE season_image LIKE '%image.tmdb.org%' ORDER BY season_id ASC LIMIT 20";
        $episodeQuery = "SELECT * FROM episodes WHERE epso_image LIKE '%image.tmdb.org%' ORDER BY episode_id ASC LIMIT 20";

        //20 rows each
        $unProcessedList[] = ['data'=>Query::query($showQuery),'table'=>'tv_shows', 'column'=>'show_image'];
        $unProcessedList[] = ['data'=> Query::query($seasonQuery),'table'=>'seasons', 'column'=>'season_image'];
        $unProcessedList[] = ['data'=> Query::query($episodeQuery), 'table'=>'episodes', 'column'=>'epso_image'];
        return $unProcessedList;
    }

    public function processImages($table, $column, $data): bool
    {
        $totalCreated = 0;
        if(!empty($table) && !empty($column) && !empty($data)){
            $idColumn = "";
            foreach ($data as $key=>$value){

                //isset then process
                if(isset($value[$column])){
                    $link = $value[$column] ?? null;
                     if(!empty($link)){
                         //reading its data
                         $linkContent = file_get_contents($link);
                         if($linkContent !== false){
                             $list = explode('.',$link);
                             top:
                             $filename = Json::uuid().'.'.end($list);
                             if(file_exists('Files/'.$filename)){
                                 goto top;
                             }

                             $imageLink = FileHandler::saveFile($filename, $linkContent,'binary');
                             if(empty($idColumn)){
                                 if(isset($value['show_id'])){
                                     $idColumn = "show_id";
                                 }elseif (isset($value['season_id'])){
                                     $idColumn = "season_id";
                                 }else{
                                     $idColumn = "episode_id";
                                 }
                             }
                             $id =[$idColumn=> $value[$idColumn]];
                             $newData = [$column=>$imageLink];

                             Updating::update($table,$newData,$id);
                             $totalCreated++;
                         }
                     }
                }
            }
        }
        return $totalCreated === count($data);
    }

    public function records(string $type, int $status, string $result): void
    {
        (new MysqlDynamicTables())->resolver(Database::database(),
        ['cron_run','cron_type', 'output'],
        ['cron_run'=>['int(11)', 'not null'], 'output'=>['text', 'not null'], 'cron_type'=>["varchar(100)", 'not null']],
            "crons"
        );
        Insertion::insertRow('crons',['cron_run'=>$status, 'cron_type'=>$type, 'output'=>$result]);
    }
    
    
    public function queryLinks(): array
    {
        $unProcessedList = [];
        $showQuery = "SELECT * FROM tv_shows WHERE show_image LIKE '%stream.quickapistorage.com%' ORDER BY show_id ASC LIMIT 20";
        $seasonQuery = "SELECT * FROM seasons WHERE season_image LIKE '%stream.quickapistorage.com%' ORDER BY season_id ASC LIMIT 20";
        $episodeQuery = "SELECT * FROM episodes WHERE epso_image LIKE '%stream.quickapistorage.com%' ORDER BY episode_id ASC LIMIT 20";
        $movies = "SELECT * FROM images WHERE url_image LIKE '%stream.quickapistorage.com%' ORDER BY image_id ASC LIMIT 50";

        //20 rows each
        $unProcessedList[] = ['data'=>Query::query($showQuery),'table'=>'tv_shows', 'column'=>'show_image'];
        $unProcessedList[] = ['data'=> Query::query($seasonQuery),'table'=>'seasons', 'column'=>'season_image'];
        $unProcessedList[] = ['data'=> Query::query($episodeQuery), 'table'=>'episodes', 'column'=>'epso_image'];
        $unProcessedList[] = ['data'=> Query::query($movies), 'table'=>'images', 'column'=>'url_image'];
        return $unProcessedList;
    }

    private function fileNotFound($link)
    {
        (new MysqlDynamicTables())->resolver(Database::database(),['filename'], ['filename'=>['varchar(250)', 'nnull']], 'not_found_filename');
        Insertion::insertRow('not_found_filename', ['filename'=>$link]);
    }

    public function renameLinks($table, $column, $data): bool
    {
        $totalCreated = 0;
        foreach ($data as $key=>$value){
            $link = $value[$column];
            $idColumn = "";
            $uuid = "";
            $list = [];
            if(!empty($link)){
               $list = explode('/', $link);
            }

            if(!file_exists(Globals::root()."/Files/".end($list))){
                $this->fileNotFound("/Files/".end($list));
            }
            $imageLink =Globals::protocal().'://'. Globals::serverHost()."/Files/".end($list);
            
            if(empty($idColumn)){
                if(isset($value['show_id'])){
                    $idColumn = "show_id";
                    $uuid = $value['show_uuid'];
                }elseif (isset($value['season_id'])){
                    $idColumn = "season_id";
                    $uuid = $value['season_uuid'];
                }elseif(isset($value['episode_id'])){
                    $idColumn = "episode_id";
                    $uuid = $value['episode_uuid'];
                }else{
                    $idColumn = "image_id";
                }
            }
            $id =[$idColumn=> $value[$idColumn]];
            $newData = [$column=>$imageLink];

            Updating::update($table,$newData,$id);
            if(!empty($uuid) && !empty($imageLink) && !empty($table)){
                $this->updateSEOs($uuid, $imageLink, $table);
            }
            $totalCreated++;
        }
        return $totalCreated === count($data);
    }

    public function updateSEOs($uuid, $imageLink, $type): void
    {
        $token = "";
        if($type === "movies"){
            $token = Globals::protocal()."://".Globals::serverHost();
            $token .= "/movie-stream?movie=".$uuid;
        }

        if($type === "tv_shows"){
            $token =  Globals::protocal()."://".Globals::serverHost()."/view-tv-show?show=".$uuid;
        }

        if($type === "seasons"){
            $token =  Globals::protocal()."://".Globals::serverHost()."/season?se=".$uuid;
        }

        SEOTags::updateSEO($token,['image'=>$imageLink]);
    }


}