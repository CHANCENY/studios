<?php

namespace Modules\Imports;

use Datainterface\Database;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Query;
use Datainterface\Updating;
use FileHandler\FileHandler;
use Json\Json;

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


}