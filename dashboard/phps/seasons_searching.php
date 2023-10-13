<?php

use ApiHandler\ApiHandlerClass;
use GlobalsFunctions\Globals;
use groups\GroupSeasons;

header("Content-Type: application/json");

$name = Globals::get("searchName");
$id = Globals::get("searchID");

$shows = [];
if($name !== "no-value" && $id !== "no-value")
{
    $shows = (new GroupSeasons())->searchBYNameAndID($name, intval($id));
}
if($name !== "no-value")
{
    $shows = (new GroupSeasons())->searchByName($name);
}
if($id !== "no-value")
{
    $shows = (new GroupSeasons())->searchByID(intval($id));
}
http_response_code(200);
echo ApiHandlerClass::stringfiyData(['results'=>$shows]);
exit;