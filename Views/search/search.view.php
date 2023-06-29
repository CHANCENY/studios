<?php


$movies = \Datainterface\Query::query("SELECT title FROM movies");
$shows = \Datainterface\Query::query("SELECT title FROM tv_shows");

$list = [];
foreach ($movies as $key=>$value){
    $list[] = $value['title'] ?? null;
}

foreach ($shows as $key=>$value){
    $list[] = $value['title'] ?? null;
}

$list2 = array_filter($list, 'strlen');

echo \ApiHandler\ApiHandlerClass::stringfiyData($list2);
exit;
?>