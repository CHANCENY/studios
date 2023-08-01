<?php

namespace Modules\Genres;

use Datainterface\Selection;

class Genre
{
    public function filteredListGenre(): array
    {
        $genres = Selection::selectAll('genres');

        $list = [];
        foreach ($genres as $key=>$genre){
            $list = array_merge($list, explode('|',$genre['genre_name'] ?? ""));
        }

        $list = array_filter($list, 'strlen');

        $newList = [];
        foreach ($list as $key=>$value){
           $newList = array_merge($newList, array_filter(explode(' ',$value), 'strlen'));
        }

        $returnArray = [];
        foreach ($newList as $key=>$item){
            if(!in_array(trim($item), $returnArray)){
                $returnArray[] = trim($item);
            }
        }
        return$returnArray;
    }

}