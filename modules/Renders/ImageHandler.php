<?php

namespace Modules\Renders;

use Datainterface\Selection;
use GlobalsFunctions\Globals;
use Modules\Modals\Debug;
use Modules\Modals\Details;


/**
 *
 */
class ImageHandler
{
    /**
     * @var string
     */
    private string $imageUUID;
    /**
     * @var array
     */
    private array $image;

    private bool $error;

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->error;
    }

    /**
     * @param string $imageUuid
     */
    public function __construct(string $imageUuid)
    {
        $this->imageUUID = $imageUuid;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function loadImage(): ImageHandler
    {
        if(!isset($this->imageUUID)){
            $this->error = true;
            throw new \Exception("image UUID is not set", 1008);
        }
        $result = Selection::selectById("images_managed",["image_uuid"=>$this->imageUUID]);
        if(!empty($result)){

            $this->image = [
                "name"=>$result[0]["image_name"],
                "size"=>$result[0]["image_size"],
                "path"=>$result[0]["image_path"],
                "url"=>$result[0]["image_url"],
                "extension"=>$result[0]["image_extension"],
                "modified"=>$result[0]["created"]
            ];
            $this->error = false;
        }else{
            $this->error = true;
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): string|null
    {
        return $this->image['name'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getSize(): int|null
    {
        return intval($this->image['size'] ?? 0) ?? null;
    }

    /**
     * @return string|null
     */
    public function getPath(): string|null
    {
        return $this->image['path'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getURL(): string|null
    {
        return $this->image['url'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getExtension(): string|null
    {
        return $this->image['extension'] ?? null;
    }

    public function setCopy(ImageHandler $object): void
    {
        if(!isset($_SESSION['image_wrapper'][$this->imageUUID])){
            $_SESSION['image_wrapper'][$this->imageUUID] = ['path'=>$object->getPath(),
                'extension'=>$object->getPath()];
        }
    }

    public function getCopy(): array
    {
        return $_SESSION['image_wrapper'][$this->imageUUID] ?? [];
    }

    public function moreImages(Details $object): array
    {

        $authToken = \functions\config('TMDB');

        $bundle = $object->getBundle() === "movies" ? "movie" : "tv";
        $tmID = $object->tmID();

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/$bundle/$tmID/images",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: $authToken",
                "accept: application/json"
            ],
        ]);

        $response = json_decode(curl_exec($curl), true);
        $err = curl_error($curl);

        curl_close($curl);

        if (empty($err)) {
          return $response['backdrops'] ?? [];
        } else {
           return [];
        }
    }


    public static function image($imageURL): string|null
    {
        $link = "";
        if(!empty($imageURL) && str_contains($imageURL, "?image=")){
            $list = explode("=", $imageURL);
            $line = trim(end($list));
            if(isset($_SESSION['images'][$line])){
                return $_SESSION['images'][$line];
            }
            $image = (new ImageHandler($line))->loadImage()->getURL();
            if(!empty($image)){
                $_SESSION['images'][$line] = Globals::protocal()."://".$image;
                return Globals::protocal()."://".$image;
            }
        }else{
            $image = (new ImageHandler($imageURL))->loadImage()->getURL();
            if(!empty($image)){
                $_SESSION['images'][$imageURL] = Globals::protocal()."://".$image;
                return Globals::protocal()."://".$image;
            }
        }
        return $imageURL;
    }

}