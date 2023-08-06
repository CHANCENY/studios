<?php

namespace Modules\Renders;

use Datainterface\Selection;
use Modules\Imports\ImagesMigrator;

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

}