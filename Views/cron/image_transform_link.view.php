<?php

$data = (new \Modules\Imports\ImageCreation())->queryLinks();

foreach ($data as $key=>$v){
    $result = (new \Modules\Imports\ImageCreation())->renameLinks($v['table'], $v['column'], $v['data']);
}
?>