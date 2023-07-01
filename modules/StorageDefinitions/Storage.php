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
        $tables = ['tv_shows', 'movies', 'related', 'seasons', 'episodes','genres', 'images'];
        $columns = [];
        $columns[] = ['show_id', 'title','description','release_date', 'show_image', 'show_uuid'];
        $columns[] = ['movie_id','title','url','duration','type','release_date','description','related_movies','movie_uuid'];
        $columns[] = ['related_id','movie_id', 'show_id'];
        $columns[] = ['season_id','season_name','show_id', 'season_image','episode_count', 'description', 'air_date', 'season_number','season_uuid', 'changed'];
        $columns[] = ['episode_id','title','url','duration','type', 'season_id', 'epso_description', 'epso_image','epso_number', 'air_date', 'publish', 'episode_uuid', 'changed'];
        $columns[] = ['genre_id', 'genre_name'];
        $columns[] = ['image_id','target_id','url_image'];

        $ids = ['int(11)', 'auto_increment', 'primary key'];

        $attributes = [];
        $attributes[$tables[0]] =["show_id"=>$ids,
            "title"=>['varchar(100)','null'],
            "description"=> ['text', 'null'],
            'release_date'=>['varchar(100)', 'null'],
            'show_image'=>['varchar(250)'],
            'show_uuid'=>['varchar(200)']
        ];

        $attributes[$tables[1]] = ["movie_id"=>$ids,
            "title"=>['varchar(100)','null'],
            "description"=> ['text', 'null'],
            "url"=> ['text', 'null'],
            "duration"=>['varchar(20)', 'null'],
            "type"=>['int(11)', 'null'],
            "related_movies"=>['varchar(20)','null'],
            "release_date"=>['varchar(50)'],
            "movie_uuid"=>['varchar(200)']
        ];

        $attributes[$tables[2]] =["related_id"=>$ids,
            "movie_id"=>['varchar(100)','not null'],
            "show_id"=>['varchar(20)','null']
        ];

        $attributes[$tables[3]] =["season_id"=>$ids,
            "season_name"=>['varchar(100)','null'],
            "show_id"=>['int(11)', 'null'],
            "season_image"=>['varchar(250)'],
            "episode_count"=>['int(11)'],
            "description"=>['text'],
            "air_date"=>['varchar(50)'],
            "season_number"=>['int(11)'],
            "season_uuid"=>['varchar(200)'],
            "changed"=>['timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP']
        ];
        $attributes[$tables[4]] =["episode_id"=>$ids,
            "title"=>['varchar(100)','null'],
            "url"=> ['text', 'null'],
            "duration"=>['varchar(20)', 'null'],
            "type"=>['int(11)', 'null'],
            "season_id"=>['int(11)','null'],
            "epso_description"=>['text'],
            "epso_image"=>['varchar(250)'],
            "epso_number"=>['int(11)'],
            "air_date"=>['varchar(50)'],
            "publish"=>["varchar(20)", "not null"],
            "episode_uuid"=>['varchar(200)'],
            "changed"=>['timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP']
        ];

        $attributes[$tables[5]] =["genre_id"=>$ids,
            "genre_name"=>['varchar(100)','not null'],
        ];

        $attributes[$tables[6]] = ['image_id'=>$ids,
            'target_id'=>['int(11)', 'not null'],
            'url_image'=>['varchar(250)', 'not null']
        ];

        return [
            'tables'=>$tables,
            'columns'=>$columns,
            'attributes'=>$attributes
        ];

    }
}