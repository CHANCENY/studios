<?php

ob_clean();
ob_flush();

$image = \GlobalsFunctions\Globals::get("image");
/**
 * Check if paramater has value
 */
if(!empty($image)){

    /**
     * load image and read it content
     */
    $imageFound = (new \Modules\Renders\ImageHandler($image))->loadImage();

    if(!$imageFound->isError()){
        /**
         * read to send image
         */
        $imagePath = $imageFound->getPath();
        $imagePath = \Core\Router::clearUrl($imagePath);
        $list = explode("sites/", $imagePath);
        header("Content-Type: image/{$imageFound->getExtension()}");
        readfile($imageFound->getPath());
        unset($imageFound);
    }
}else{
    /**
     * show default no image exist
     */
}
exit;
