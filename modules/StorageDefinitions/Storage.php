<?php

namespace Modules\StorageDefinitions;

use Datainterface\Database;
use Datainterface\MysqlDynamicTables;

class Storage
{
   public function __construct()
   {
       $this->runAll();
   }

    private function runAll()
    {
        $schmas = $this->schemaAll();
        $tables = $schmas['tables'];
        foreach ($tables as $key=>$table){
            $columns = $schmas['columns'][$key];
            $attributes = $schmas['attributes'][$table];
            (new MysqlDynamicTables())->resolver(Database::database(),
                $columns,
                $attributes,
                $table,
                false
            );
        }
    }

    public function schemaAll(): array
    {
        $tables = ['tv_shows', 'movies', 'related', 'seasons', 'episodes','genres'];
        $columns = [];
        $columns[] = ['show_id', 'title','description','release_date'];
        $columns[] = ['movie_id','title','url','duration','type','description','related_movies'];
        $columns[] = ['related_id','movie_id', 'show_id'];
        $columns[] = ['season_id','season_name','show_id'];
        $columns[] = ['episode_id','title','url','duration','type', 'season_id'];
        $columns[] = ['genre_id', 'genre_name'];

        $ids = ['int(11)', 'auto_increment', 'primary key'];

        $attributes = [];
        $attributes[$tables[0]] =["show_id"=>$ids,
            "title"=>['varchar(100)','not null'],
            "description"=> ['text', 'null'],
            'release_date'=>['varchar(100)', 'not null']
        ];

        $attributes[$tables[1]] = ["movie_id"=>$ids,
            "title"=>['varchar(100)','not null'],
            "description"=> ['text', 'null'],
            "url"=> ['text', 'not null'],
            "duration"=>['varchar(20)', 'null'],
            "type"=>['int(11)', 'null'],
            "related_movies"=>['varchar(20)','null']
        ];

        $attributes[$tables[2]] =["related_id"=>$ids,
            "movie_id"=>['varchar(100)','not null'],
            "show_id"=>['varchar(20)','null']
        ];

        $attributes[$tables[3]] =["season_id"=>$ids,
            "season_name"=>['varchar(100)','not null'],
            "show_id"=>['int(11)', 'null']
        ];

        $attributes[$tables[4]] =["episode_id"=>$ids,
            "title"=>['varchar(100)','not null'],
            "url"=> ['text', 'not null'],
            "duration"=>['varchar(20)', 'null'],
            "type"=>['int(11)', 'null'],
            "season_id"=>['int(11)','not null']
        ];

        $attributes[$tables[5]] =["genre_id"=>$ids,
            "genre_name"=>['varchar(100)','not null'],
        ];

        return [
            'tables'=>$tables,
            'columns'=>$columns,
            'attributes'=>$attributes
        ];

    }
}