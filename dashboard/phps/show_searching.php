<?php

use ApiHandler\ApiHandlerClass;
use GlobalsFunctions\Globals;
use groups\GroupShows;

header("Content-Type: application/json");

$name = Globals::get("searchName");
$id = Globals::get("searchID");

$shows = [];
if($name !== "no-value" && $id !== "no-value")
{
    $shows = (new GroupShows())->searchBYNameAndID($name, intval($id));
    echo ApiHandlerClass::stringfiyData($shows);
    exit;
}
if($name !== "no-value")
{
    $shows = (new GroupShows())->searchByName($name);
}
if($id !== "no-value")
{
    $shows = (new GroupShows())->searchByID(intval($id));
}
http_response_code(200);
echo ApiHandlerClass::stringfiyData(['results'=>$shows]);
exit;