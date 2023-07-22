<?php

namespace Modules\Movies;

use Json\Json;
use Datainterface\Query;
use Datainterface\Delete;
use Datainterface\Updating;
use Datainterface\Insertion;
use Datainterface\Selection;
use FileHandler\FileHandler;
use GlobalsFunctions\Globals;
use Modules\NewAlerts\SubcriberNews;
use Modules\Renders\SEOTags;
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
        $uuid = "";
        //combine
        $data = [];
        $seoData = [];
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
        $data['movie_uuid'] = Json::uuid();
        $uuid = $data['movie_uuid'];
        $movieId = Insertion::insertRow($tableMovie, $data);

        $seoData['title'] = $data['title'];
        $seoData['description'] = $data['description'];
        $seoData['keywords'] = $data['title'].','.implode(',', explode(' ', $data['description']));
        $seoData['canonical'] = Globals::protocal()."://".Globals::serverHost()."/movie-stream?movie=".$uuid;
        $seoData['video'] = $data['url'];

        $newMessage = "<p>Hello Our subscriber Stream studios has upload new movie which you can watch on our site</p>";
        $newMessage .="<p>Movie titled: {$data['title']}<br>
                          Movie summary: {$data['description']}</p>";
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
        $seoData['image'] = $data['url_image'];

        $img = Globals::protocal().'://'.Globals::serverHost().'/'.$data['url_image'];
        $newMessage .= "<img src='{$img}' style='width: 20rem;'>";
        $home =Globals::protocal().'://'. Globals::serverHost().'/watch?m='.$uuid;
        $newMessage .= "<a href='$home' style='width: fit-content; padding: 5px; background-color: orange;color: black; border: 1px solid orange; border-radius: 5px;'>Click To Watch</a>";
        (new SubcriberNews('New Movies'))->saveEvent($newMessage);

        $token = SEOTags::getToken($seoData['canonical']);
        $seoData['url'] = $token;
        $seoData = SEOTags::create($seoData);
        (new SEOTags($token))->data($seoData)->set();

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
        $query = "SELECT * FROM $movieT AS m LEFT JOIN $imageT AS im ON im.target_id = m.movie_id LEFT JOIN $typeT AS g ON g.genre_id = m.type ORDER BY m.movie_changed DESC";
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
        $movie = Selection::selectById('movies', ['movie_id'=>$movie_id]);
        $title =  $data['title'] ?? $movie[0]['title'] ?? null;
        $desc =  $data['description'] ?? $movie[0]['description'] ?? null;
        $image = $this->movieImage($movie[0]['movie_id'] ?? 0);

        $newMessage = "<p>Hello Our subscriber Stream studios has updated movie which you can watch on our site</p>";
        $newMessage .="<p>Movie titled: {$title}<br>
                          Movie summary: {$desc}</p>";
        $newMessage .= "<img src='{$image}' style='width: 20rem;'>";
        $home =Globals::protocal().'://'. Globals::serverHost().'/watch?m='.$movie[0]['movie_uuid'] ?? null;
        $newMessage .= "<a href='$home' style='width: fit-content; padding: 5px; background-color: orange;color: black; border: 1px solid orange; border-radius: 5px;'>Click To Watch</a>";
        (new SubcriberNews('New Movies'))->saveEvent($newMessage);

        $seo['video'] = $data['url'];
        $seo['title'] = $title;
        $seo['description'] = $desc;
        $seo['image'] = $image;
        $token = Globals::protocal().'://'.Globals::serverHost().'/movie-stream?movie='.$movie[0]['movie_uuid'] ?? null;
        SEOTags::updateSEO($token,$seo);
       return Updating::update('movies',$data, ['movie_id'=>$movie_id]);
    }

    public function delete(false|string $get)
    {
        if($get === false){
            return false;
        }
        $result = Selection::selectById('images',['target_id'=>$get]);
        $file = $result[0]['url_image'] ?? "";
        if(file_exists($file)){
            @unlink($file);
        }
        Delete::delete('images',['target_id'=>$get]);
        return Delete::delete('movies',['movie_id'=>$get]);
    }


    public function movieImage($movie_id): string
    {
        return Query::query("SELECT url_image FROM images WHERE target_id = $movie_id")[0]['url_image'] ?? "";
    }

}