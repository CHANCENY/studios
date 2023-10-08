<?php

namespace groups;

use Datainterface\Delete;
use Datainterface\Insertion;
use Datainterface\Query;
use Datainterface\Selection;
use Datainterface\Updating;
use Json\Json;
use Modules\Renders\ImageHandler;


class GroupMovies
{
    public function latestMoviesUploaded(): array
    {
        $movies = Query::query("SELECT title AS name, 
       movie_image AS image, 
       release_date AS date, 
       movie_id AS id, 
       duration AS time,
        CASE
              WHEN url IS NOT NULL THEN 1
              ELSE 0
          END AS active
        FROM movies ORDER BY created DESC");

        if(!empty($movies))
        {
            return array_values($movies);
        }
        return array();
    }

    public function movieToEdit(int $movieID): array
    {
        return array_values(Selection::selectById("movies",['movie_id'=>$movieID]));
    }

    public function movieDelete(int $movieID): bool
    {
        /**
         * Delete from movies
         * Delete from images_managed
         * Delete from additional_information
         */
        $movie = Selection::selectById("movies",['movie_id'=>$movieID]);
        if(!empty($movie))
        {
            try {
                $image = $movie[0]['movie_image'] ?? null;
                $imageID = !empty($image) ? explode('=', $image) : [];
                $imageID = end($imageID);
                $path = (new ImageHandler($imageID))->loadImage()->getPath();
                $deletedMovie = [];
                if($path)
                {
                    $deletedMovie[] = unlink($path);
                }
                Delete::delete("movies",["movie_id"=>$movieID]);
                Delete::delete("images_managed", ['image_uuid'=>$imageID]);
                Delete::delete("images", ['target_id'=>$movieID]);
                Query::query("DELETE FROM additional_information WHERE bundle = :b AND internal_id = :id", ['id'=>$movieID, "b"=>"movies"]);
                return true;
            }
            catch (\Throwable $e)
            {
                return false;
            }
        }
        return false;
    }

    public function searchByName(string $name): array
    {
        $movies = Query::query("SELECT title AS name, 
       movie_image AS image, 
       release_date AS date, 
       movie_id AS id, 
       duration AS time,
        CASE
              WHEN url IS NOT NULL THEN 1
              ELSE 0
          END AS active
        FROM movies WHERE title LIKE '%$name%' ORDER BY created DESC");

        if(!empty($movies))
        {
            return array_values($movies);
        }
        return array();
    }

    public function searchByID(int $id): array
    {
        $movies = Query::query("SELECT title AS name, 
       movie_image AS image, 
       release_date AS date, 
       movie_id AS id, 
       duration AS time,
        CASE
              WHEN url IS NOT NULL THEN 1
              ELSE 0
          END AS active
        FROM movies WHERE movie_id = :id ORDER BY created DESC", ['id'=>$id]);

        if(!empty($movies))
        {
            return array_values($movies);
        }
        return array();
    }

    public function searchBYNameAndID(string $name, int $id): array{
        $movies = Query::query("SELECT title AS name, 
       movie_image AS image, 
       release_date AS date, 
       movie_id AS id, 
       duration AS time,
        CASE
              WHEN url IS NOT NULL THEN 1
              ELSE 0
          END AS active
        FROM movies WHERE movie_id = :id || title = :name ORDER BY created DESC", ['id'=>$id, "name"=>$name]);

        if(!empty($movies))
        {
            return array_values($movies);
        }
        return array();
    }


    public function newImage($file, $oldImageID, $movie): string|false
    {
        $name = $file['name'];
        $size = $file['size'];
        $tmp = $file['tmp_name'];
        if($size < 200000)
        {
            $extension = explode(".", $name);
            $extension = end($extension);
            if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
            {
                $imageUrl = \FileHandler\FileHandler::saveFile($name,$tmp);
                $list = explode("/", $imageUrl);
                $tempFile = "Files/". end($list);
                $path = "../sites/files/images/movies/". end($list);
                if(file_exists($path))
                {
                    path:
                    $ext = explode(".",$path);
                    $path = "../sites/files/images/movies/".Json::uuid().".".end($ext);
                    if(file_exists($path))
                    {
                        goto path;
                    }

                }

                if(copy($tempFile, $path)){
                    $path = trim($path, ".");
                    $path = trim($path, "/");
                    $spl = new \SplFileInfo("../".$path);
                    $data['image_extension'] = $spl->getExtension();
                    $data['image_name'] = $spl->getFilename();
                    $data['image_path'] = $spl->getRealPath();
                    $data['image_size'] = $spl->getSize();
                    $data['image_url'] = "streamstudios.online/".$path;
                    $data['image_uuid'] = Json::uuid();

                    if(Insertion::insertRow("images_managed",$data))
                    {
                        $newImage = "https://streamstudios.online/img?image=".$data['image_uuid'];
                        if(Updating::update("images",['url_image'=>$newImage],['target_id'=>$movie]))
                        {
                            Query::query("DELETE FROM images_managed WHERE image_uuid = :uuid",['uuid'=>$oldImageID]);
                            unlink($tempFile);
                            return $newImage;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function uploadNewImage($file, $movie): string|false
    {
        $name = $file['name'];
        $size = $file['size'];
        $tmp = $file['tmp_name'];
        if($size < 200000)
        {
            $extension = explode(".", $name);
            $extension = end($extension);
            if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
            {
                $imageUrl = \FileHandler\FileHandler::saveFile($name,$tmp);
                $list = explode("/", $imageUrl);
                $tempFile = "Files/". end($list);
                $path = "../sites/files/images/movies/". end($list);
                if(file_exists($path))
                {
                    path:
                    $ext = explode(".",$path);
                    $path = "../sites/files/images/movies/".Json::uuid().".".end($ext);
                    if(file_exists($path))
                    {
                        goto path;
                    }

                }

                if(copy($tempFile, $path)){
                    $path = trim($path, ".");
                    $path = trim($path, "/");
                    $spl = new \SplFileInfo("../".$path);
                    $data['image_extension'] = $spl->getExtension();
                    $data['image_name'] = $spl->getFilename();
                    $data['image_path'] = $spl->getRealPath();
                    $data['image_size'] = $spl->getSize();
                    $data['image_url'] = "streamstudios.online/".$path;
                    $data['image_uuid'] = Json::uuid();

                    if(Insertion::insertRow("images_managed",$data))
                    {
                        $newImage = "https://streamstudios.online/img?image=".$data['image_uuid'];
                        if(Insertion::insertRow("images",['url_image'=>$newImage,'target_id'=>$movie]))
                        {
                            unlink($tempFile);
                            (new Notifications())->imageUploaded($newImage);
                            return $newImage;
                        }
                    }
                }
            }
        }
        return false;
    }


    public function removeNewUploadedImage($imageUUID, $movie): bool
    {
        if(Delete::delete("images",['target_id'=>$movie]))
        {
            $image = (new ImageHandler($imageUUID))->loadImage()->getPath();
            if(Delete::delete("images_managed", ['image_uuid'=>$imageUUID]))
            {
                if(!empty($image))
                {
                    (new Notifications())->imageDeleted("IMG-".$imageUUID);
                    unlink($image);
                }
                return true;
            }
        }
        return false;
    }


    public function uploadImageFromUrl($url, $movie): string|false
    {

        $extension = explode(".", $url);
        $extension = end($extension);
        if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
        {
            $name = explode("/", $url);
            $filename = end($name);
            $imageUrl = \FileHandler\FileHandler::saveFile($filename,file_get_contents($url), 'binary');
            $list = explode("/", $imageUrl);
            $tempFile = "Files/". end($list);
            $path = "../sites/files/images/movies/". end($list);
            if(file_exists($path))
            {
                path:
                $ext = explode(".",$path);
                $path = "../sites/files/images/movies/".Json::uuid().".".end($ext);
                if(file_exists($path))
                {
                    goto path;
                }

            }

            if(copy($tempFile, $path)){
                $path = trim($path, ".");
                $path = trim($path, "/");
                $spl = new \SplFileInfo("../".$path);
                $data['image_extension'] = $spl->getExtension();
                $data['image_name'] = $spl->getFilename();
                $data['image_path'] = $spl->getRealPath();
                $data['image_size'] = $spl->getSize();
                $data['image_url'] = "streamstudios.online/".$path;
                $data['image_uuid'] = Json::uuid();

                if(Insertion::insertRow("images_managed",$data))
                {
                    $newImage = "https://streamstudios.online/img?image=".$data['image_uuid'];
                    if(Insertion::insertRow("images",['url_image'=>$newImage,'target_id'=>$movie]))
                    {
                        unlink($tempFile);
                        (new Notifications())->imageUploaded($newImage);
                        return $newImage;
                    }
                }
            }
        }

        return false;
    }

    public function expectedRowID(): int
    {
        $database = \Datainterface\Database::getDbname();
        $query = "SELECT AUTO_INCREMENT  FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = 'movies'";
        $data = \Datainterface\Query::query($query);
        if(!empty($data))
        {
            return $data[0]["AUTO_INCREMENT"] ?? 0;
        }
        return 0;
    }


    public function saveAdditionalInfo($movie, $internalID): bool
    {
        $data['internal_id'] = $internalID;
        $data['bundle'] = "movies";
        $data['tm_id'] = $movie['id'] ?? 0;
        $data['popularity'] = $movie['popularity'] ?? 0;
        $data['vote_average'] = $movie['vote_average'] ?? 0;
        $data['vote_count'] = $movie['vote_count'] ?? 0;
        $data['original_language'] = $movie['original_language'] ?? "";
        $data['origin_country'] = $this->originCountry($movie);
        $data['genres'] = $this->genres($movie);
        if(Insertion::insertRow("additional_information", $data)){
            return true;
        }
        return false;
    }

    public function originCountry ($movie){
        $line = [];
        foreach ($movie['production_countries'] as $key=>$value){
            $line[] = $value['iso_3166_1'] ?? "";
        }
        return implode(",", $line);
    }

    public function genres ($movie){
        $line = [];
        foreach ($movie['genres'] as $key=>$value) {
            $line[] = $value['name'];
        }
        return implode(",", $line);
    }

}