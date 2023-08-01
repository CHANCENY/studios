<?php

namespace Modules\TypeModerator;

use Datainterface\Query;
use Datainterface\Selection;

class EntityCategories
{

    public function categories(): array
    {
        return Selection::selectAll("categories_entities");
    }

    public function category(int $category_id): array
    {
        return Selection::selectById("categories_entities", ["categorg_id"=>$category_id]);
    }

    public function getByName(string $category_name): array
    {
        return Selection::selectById("entities_groups",["category_name"=>$category_name]);
    }
    public function get(int $entity_id = 0, string $category_name = "", string $bundle = ""): array|false
    {
       $query = [];
       if(!empty($entity_id))
       {
           $query[] = ["entity_id"=>$entity_id];
       }

       if(!empty($category_name))
       {
           $query[] = ["category_name"=>$category_name];
       }

       if(!empty($bundle))
       {
           $query[] = ["bundle"=>$bundle];
       }

       $line = "";
       foreach ($query as $key=>$value)
       {
           $key = array_keys($value);
           $line .= "{$key[0]} = '{$value[$key[0]]}' AND ";
       }
       $line = trim(substr($line, 0, strlen($line) - 4));

       return Query::query("SELECT * FROM entities_groups WHERE $line");
    }
}