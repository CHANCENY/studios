<?php

use ApiHandler\ApiHandlerClass;
use GlobalsFunctions\Globals;
use groups\GroupMovies;

header("Content-Type: application/json");

$name = Globals::get("searchName");
$id = Globals::get("searchID");

$movies = [];

if($name !== "no-value" && $id !== "no-value")
{
    $movies = (new GroupMovies())->searchBYNameAndID($name, intval($id));
}
if($name !== "no-value")
{
    $movies = (new GroupMovies())->searchByName($name);
}
if($id !== "no-value")
{
    $movies = (new GroupMovies())->searchByID(intval($id));
}
http_response_code(200);
echo ApiHandlerClass::stringfiyData(['results'=>$movies]);
exit;