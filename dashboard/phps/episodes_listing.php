<?php
header("Content-Type: application/json");
$episodes = (new \groups\GroupEpisodes())->latestMoviesUploaded();

http_response_code(200);

$page = \GlobalsFunctions\Globals::get("page");
$episodes = array_chunk($episodes, 20);
if(!empty($page))
{

    if(isset($episodes[$page]))
    {
        $outGoingList = $episodes[$page];
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['results'=>$outGoingList]);
    }
    else{
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['results'=>"/episodes/listing"]);
    }
}else{
    $outGoingList = $episodes[0];
    echo \ApiHandler\ApiHandlerClass::stringfiyData(['results'=>$outGoingList]);
}
exit;