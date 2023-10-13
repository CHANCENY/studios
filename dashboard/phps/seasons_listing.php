<?php
header("Content-Type: application/json");
$movies = (new \groups\GroupSeasons())->seasonsListing();

http_response_code(200);

$page = \GlobalsFunctions\Globals::get("page");
$movies = array_chunk($movies, 20);
if(!empty($page))
{

    if(isset($movies[$page]))
    {
        $outGoingList = $movies[$page];
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['results'=>$outGoingList]);
    }
    else{
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['results'=>"/seasons/listing"]);
    }
}else{
    $outGoingList = $movies[0];
    echo \ApiHandler\ApiHandlerClass::stringfiyData(['results'=>$outGoingList]);
}
exit;