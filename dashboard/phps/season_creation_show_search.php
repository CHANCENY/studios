<?php
header("Content-Type: application/json");

if(\GlobalsFunctions\Globals::get("title"))
{
    $title = \GlobalsFunctions\Globals::get("title");
    $data = \Datainterface\Query::query("SELECT show_id AS id, title AS name FROM tv_shows WHERE title LIKE '%$title%'");
    if(!empty($data))
    {
        http_response_code(200);
        echo \ApiHandler\ApiHandlerClass::stringfiyData(array_values($data));
        exit;
    }else{
        http_response_code(404);
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['error'=>true]);
        exit;
    }

}
