<?php
use ApiHandler\ApiHandlerClass;
use GlobalsFunctions\Globals;
use groups\GroupShows;

$id = Globals::get("id");
$type = Globals::get("type");

if(!empty($type) && !empty($id))
{
    http_response_code(200);
    echo ApiHandlerClass::stringfiyData(['status'=>
     match ($type){
        "show"=>(new GroupShows())->deleteShow($id),
         "season"=>(new GroupShows())->deleteSeason($id),
         "episode"=>(new GroupShows())->deleteEpisode($id),
         default=>false
     }
    ]);
    exit;
}
