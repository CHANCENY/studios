<?php

namespace Modules\Renders;

use Datainterface\Query;

class BackGroundImages
{
    public function load(): array
    {
        $query1 = "SELECT url_image FROM images order by rand() LIMIT 5";
        $query2 = "SELECT show_image FROM tv_shows ORDER BY rand() LIMIT 5";
        $result = [];

        foreach (Query::query($query1) ?? [] as $key=>$value){
            $result[] = ["image"=>trim($value['url_image'])];
        }
        foreach (Query::query($query2) ?? [] as $key=>$value){
            $result[] = ["image"=>trim($value['show_image'])];
        }
        return $result;
    }

}