<?php

namespace Modules\Movies;

use Datainterface\Insertion;
use Datainterface\Query;
use Datainterface\Selection;
use Datainterface\Updating;
use FileHandler\FileHandler;
use GlobalsFunctions\Globals;
use Modules\StorageDefinitions\Storage;

class Movie extends Storage
{

    /**
     * @var array ['movie_id','title','url','duration','type','description','related_movies']
     */
    private array $schema;

    public function __construct()
    {
        parent::__construct();
        $this->schema = $this->schemaAll();
    }

    public function addMovie(array $importer_data = []): array
    {
        //movie basic save
        $tableMovie = $this->schema['tables'][1];
        $fields = $this->schema['columns'][1];

        //combine
        $data = [];
        if(empty($importer_data)){
            foreach ($fields as $key=>$value){
                if(!empty(Globals::post($value))){
                    $data[$value] = Globals::post($value);
                }
            }
        }else{
            $data = $importer_data;
        }

        $data[$fields[4]] = str_replace(',',' | ', $data[$fields[4]]);
        $related = $this->getMovieByGenre(explode('|', $data[$fields[4]]));

        $ids = "";
        foreach ($related as $key=>$value){
            $ids .= strval($value['genre_id']).',';
        }

        $data['related_movies'] = substr($ids,0, strlen($ids) - 1);
        $typeId = Insertion::insertRow($this->schema['tables'][5], ['genre_name'=>$data[$fields[4]]]);
        $data[$fields[4]] = $typeId;

        $movieId = Insertion::insertRow($tableMovie, $data);

        unset($data);
        $data['target_id'] = $movieId;
        $file = Globals::files('image') ?? $importer_data['image'];

        $filename = $file['name'];
        $tmp = $file['tmp_name'];
        $size = $file['size'];
        $ext = (new \SplFileInfo($filename))->getExtension();

        if(in_array($ext, ['jpg','png','jpeg'])){

            global $MAXFILESIZE;
            if($size < $MAXFILESIZE){
              $data['url_image'] = FileHandler::saveFile($filename, $tmp);
              if(Globals::serverHost() === 'localhost'){
                  $list = explode('/', $data['url_image']);
                  $data['url_image'] = "Files/". end($list);
              }
            }
        }
        $fid = Insertion::insertRow($this->schema['tables'][6], $data);
        return [
            'movieId' => $movieId,
            'genreId'=>$typeId,
            'thumbnailId'=>$fid,
            "relatedIds"=>$ids
        ];

    }

    public function getMovieByGenre(array $genres): array
    {
        $line = implode('|', $genres);
        $table = $this->schema['tables'][5];
        return Query::query("SELECT * FROM $table WHERE genre_name LIKE '%$line%'");
    }

    public function movies(): array
    {
        $movieT = $this->schema['tables'][1];
        $imageT = $this->schema['tables'][6];
        $typeT = $this->schema['tables'][5];
        $query = "SELECT * FROM $movieT AS m LEFT JOIN $imageT AS im ON im.target_id = m.movie_id LEFT JOIN $typeT AS g ON g.genre_id = m.type";
        return Query::query($query);
    }

    public function getMovie($movie_id): array
    {
        $movieT = $this->schema['tables'][1];
        $imageT = $this->schema['tables'][6];
        $typeT = $this->schema['tables'][5];
        $query = "SELECT * FROM $movieT AS m LEFT JOIN $imageT AS im ON im.target_id = m.movie_id LEFT JOIN $typeT AS g ON g.genre_id = m.type WHERE m.movie_id = :id";
        return Query::query($query,['id'=>$movie_id]);
    }

    public function updateMovie($data, $movie_id): bool
    {
       return Updating::update('movies',$data, ['movie_id'=>$movie_id]);
    }

}