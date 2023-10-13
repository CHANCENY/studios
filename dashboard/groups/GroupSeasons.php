<?php

namespace groups;

use Datainterface\Insertion;
use Datainterface\Query;
use Json\Json;

class GroupSeasons
{
    public function loadSeasonByShoID($showID): array
    {
        $query = "SELECT * FROM seasons WHERE show_id = :id";
        return Query::query($query, ['id'=>$showID]);
    }

    public function seasonImageUpload($file): string
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
                $path = "../sites/files/images/seasons/". end($list);
                if(file_exists($path))
                {
                    path:
                    $ext = explode(".",$path);
                    $path = "../sites/files/images/seasons/".Json::uuid().".".end($ext);
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
                        unlink($tempFile);
                        (new Notifications())->imageUploaded($newImage);
                        return $newImage;
                    }
                }
            }
        }
        return false;
    }

    public function seasonsListing(): array
    {
        $data = Query::query("SELECT season_id AS id, season_name AS name, season_image AS image, 
        show_id AS entity_id, air_date AS date, episode_count AS episodes, season_number AS number FROM seasons ORDER BY created DESC");
        if(!empty($data))
        {
            return array_values($data);
        }
        return [];
    }

    public function searchByName(string $name): array
    {
        $movies =  Query::query("SELECT season_id AS id, season_name AS name, season_image AS image, 
        show_id AS entity_id, air_date AS date, episode_count AS episodes, season_number AS number FROM seasons WHERE season_name LIKE '%$name%' ORDER BY created DESC");

        if(!empty($movies))
        {
            return array_values($movies);
        }
        return array();
    }

    public function searchByID(int $id): array
    {
        $movies =  Query::query("SELECT season_id AS id, season_name AS name, season_image AS image, 
        show_id AS entity_id, air_date AS date, episode_count AS episodes, season_number AS number FROM seasons WHERE season_id = :id ORDER BY created DESC", ['id'=>$id]);

        if(!empty($movies))
        {
            return array_values($movies);
        }
        return array();
    }

    public function searchBYNameAndID(string $name, int $id): array{
        $movies =  Query::query("SELECT season_id AS id, season_name AS name, season_image AS image, 
        show_id AS entity_id, air_date AS date, episode_count AS episodes, season_number AS number FROM seasons WHER season_id = :id AND season_name LIKE '%$name%' ORDER BY created DESC", ['id'=>$id]);

        if(!empty($movies))
        {
            return array_values($movies);
        }
        return array();
    }

}