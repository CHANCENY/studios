<?php

namespace Modules\Imports;

use Datainterface\Database;
use Datainterface\Delete;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Query;
use Datainterface\Selection;
use Datainterface\Updating;
use ErrorLogger\ErrorLogger;
use FileHandler\FileHandler;
use GlobalsFunctions\Globals;
use Json\Json;
use Mailling\Mails;
use Sessions\SessionManager;
use function functions\config;

/**
 *
 */
class ImagesMigrator
{
    /**
     * @param int $limit
     * @return array
     */
    public function loadImage(int $limit = 0): array
  {
      $limit = $limit === 0 ? 100 : $limit;
      $fromImagesTable = "SELECT * FROM images WHERE url_image NOT LIKE '%/img?image=%' ORDER BY image_id LIMIT $limit";
      $fromTvShows = "SELECT * FROM tv_shows WHERE show_image NOT LIKE '%/img?image=%' ORDER BY show_id LIMIT $limit";
      $fromSeason = "SELECT * FROM seasons WHERE season_image NOT LIKE '%/img?image=%' ORDER BY season_id LIMIT $limit";
      $fromEpisodes = "SELECT * FROM episodes WHERE epso_image NOT LIKE '%/img?image=%' ORDER BY episode_id LIMIT $limit";

      $result = Query::query($fromImagesTable);
      $result1 = Query::query($fromTvShows);
      $result2 = Query::query($fromSeason);
      $result3 = Query::query($fromEpisodes);
      $images = [];

      foreach ($result as $key=>$value){
          $images["images"][] = $value;
      }

      foreach ($result1 as $key=>$value){
          $images["shows"][] = $value;
      }

      foreach ($result2 as $key=>$value){
          $images["seasons"][] = $value;
      }

      foreach ($result3 as $key=>$value){
          $images["episodes"][] = $value;
      }
      
    
      return $images;
  }

    /**
     * @return bool
     */
    public function moveImages(int $limit = 0): bool
  {
      $results = [];
      $images = $this->loadImage($limit);

      if(isset($images['images'])&&
          isset($images['shows']) &&
          isset($images['seasons']) &&
          isset($images['episodes']) &&
          empty($images['images']) &&
          empty($images['shows']) &&
          empty($images['seasons']) &&
          empty($images['episodes']))
      {
          $data = [
              "subject"=>"Image Conversion Completed",
              "message"=>"<p>This is Alert to let you know that all images converted</p>",
              "altbody"=>"transformation image.",
              "user"=>[config("MAIL-NOTIFY")],
              "reply"=>false,
              "attached"=>false
          ];
          Mails::send($data,"notify");
      }



      foreach ($images as $key=>$bundle){

          foreach ($bundle as $k=>$value){
              $dir = "sites/files/images";
              if($key === "images"){
                  $this->dirCreation("$dir/movies");
                  $results[] = $this->convertFromImages($dir, $value);
              }
              elseif ($key === "shows"){
                  $this->dirCreation("$dir/shows");
                 $result[] = $this->convertFromShows($dir, $value);
              }
              elseif ($key === "seasons"){
                  $this->dirCreation("$dir/seasons");
                  $result[] = $this->convertFromSeasons($dir, $value);
              }
              elseif ($key === "episodes"){
                  $this->dirCreation("$dir/episodes");
                  $result[] = $this->convertFromEpisodes($dir,$value);
              }
          }
      }
      return false;
  }


  private function dirCreation($dir): bool
  {
      if(!is_dir($dir)){
         return mkdir($dir, 7777,true);
      }
      return true;
  }

    /**
     * @param string $dir
     * @param mixed $value
     * @return bool
     */
    private function convertFromImages(string $dir, mixed $value): int|bool
    {
        if(!empty($dir) && !empty($value)){
            $image = $value['url_image'];
            $id = $value['target_id'];

            $list = explode('/', $image);
            $image = end($list);

            if(is_file(Globals::root()."/Files/$image")){
                $tempStore = file_get_contents(Globals::root()."/Files/$image");
                if(copy("Files/$image", $dir."/movies/".$image)){
                    $newId = $this->imagesDB($dir."/movies/".$image);
                    if(empty($newId)){
                        file_put_contents(Globals::root()."/Files/$image", $tempStore);
                        return false;
                    }
                    Updating::update("images",["url_image"=>$this->url($newId)], ["image_id"=>$value['image_id']]);
                    return Updating::update("movies",["movie_image"=>$this->url($newId)], ["movie_id"=>$id]);

                }else{
                    file_put_contents(Globals::root()."/Files/$image", $tempStore);
                }
            }
        }
        return false;
    }

    /**
     * @param string $path
     * @return string|false
     */
    private function imagesDB(string $path): string|false
    {
        (new MysqlDynamicTables())->resolver(
            Database::database(),
            ["image_id", "image_uuid", "image_url", "image_path", "image_name", "image_size", "image_extension"],
            [
                "image_id"=>["int(11)", "auto_increment", "primary key"],
                "image_url"=>["varchar(250)", "not null"],
                "image_name"=>["varchar(250)", "not null"],
                "image_path"=>["varchar(250)", "not null"],
                "image_size"=>["int(11)"],
                "image_extension"=>["varchar(20)", "not null"],
                "image_uuid"=>["varchar(250)", "not null"]
            ],
            "images_managed",
            false
        );
        try{
            $data = $this->image($path);
            if(empty($data)){
                throw new \Exception("False return from image creation",8989);
            }
            Insertion::insertRow("images_managed", $data);
            return $data['image_uuid'];
        }catch (\Throwable $e){
            ErrorLogger::log($e);
            return false;
        }
    }

    /**
     * @param string $path
     * @return array|false
     */
    private function image(string $path)
    {
        if(empty($path)){
            return false;
        }

        $spl = new \SplFileInfo($path);
        $data['image_extension'] = $spl->getExtension();
        $data['image_name'] = $spl->getFilename();
        $data['image_path'] = $spl->getRealPath();
        $data['image_size'] = $spl->getSize();
        $data['image_url'] = Globals::serverHost()."/".$path;
        $data['image_uuid'] = $this->uuid();
        return $data;
    }

    /**
     * @return string
     */
    private function uuid()
    {
        back:
        $uuid = Json::uuid();
        if(Selection::selectById("images_managed", ["image_uuid"=>$uuid])){
            goto back;
        }
        return $uuid;
    }


    private function url(string $newId)
    {
        return Globals::protocal()."://".Globals::serverHost()."/img?image=$newId";
    }


    private function convertFromShows(string $dir, mixed $value): int|bool
    {
        if(!empty($dir) && !empty($value)){
            $image = $value['show_image'];
            $id = $value['show_id'];

            $list = explode('/', $image);
            $image = end($list);


            if(is_file(Globals::root()."/Files/$image")){
                $tempStore = file_get_contents(Globals::root()."/Files/$image");
                if(copy("Files/$image", $dir."/shows/".$image)){
                    $newId = $this->imagesDB($dir."/shows/".$image);
                    if(empty($newId)){
                        file_put_contents(Globals::root()."/Files/$image", $tempStore);
                        return false;
                    }
                    return Updating::update("tv_shows",["show_image"=>$this->url($newId)], ["show_id"=>$id]);
                }else{
                    file_put_contents(Globals::root()."/Files/$image", $tempStore);
                }
            }
        }
        return false;
    }


    private function convertFromSeasons(string $dir, mixed $value): int|bool
    {
        if(!empty($dir) && !empty($value)){
            $image = $value['season_image'];
            $id = $value['season_id'];

            $list = explode('/', $image);
            $image = end($list);

            if(is_file(Globals::root()."/Files/$image")){
                $tempStore = file_get_contents(Globals::root()."/Files/$image");
                if(copy("Files/$image", $dir."/seasons/".$image)){
                    $newId = $this->imagesDB($dir."/seasons/".$image);
                    if(empty($newId)){
                        file_put_contents(Globals::root()."/Files/$image", $tempStore);
                        return false;
                    }
                    return Updating::update("seasons",["season_image"=>$this->url($newId)], ["season_id"=>$id]);
                }else{
                    file_put_contents(Globals::root()."/Files/$image", $tempStore);
                }
            }
        }
        return false;
    }


    private function convertFromEpisodes(string $dir, mixed $value): int|bool
    {
        if(!empty($dir) && !empty($value)){
            $image = $value['epso_image'];
            $id = $value['episode_id'];

            $list = explode('/', $image);
            $image = end($list);

            if(is_file(Globals::root()."/Files/$image")){
                $tempStore = file_get_contents(Globals::root()."/Files/$image");
                if(copy("Files/$image", $dir."/episodes/".$image)){
                    $newId = $this->imagesDB($dir."/episodes/".$image);
                    if(empty($newId)){
                        file_put_contents(Globals::root()."/Files/$image", $tempStore);
                        return false;
                    }
                    return Updating::update("episodes",["epso_image"=>$this->url($newId)], ["episode_id"=>$id]);
                }else{
                    file_put_contents(Globals::root()."/Files/$image", $tempStore);
                }
            }
        }
        return false;
    }


}