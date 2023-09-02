<?php

ob_clean();
ob_flush();

@session_start();

use GlobalsFunctions\Globals;

$image = Globals::get("image");
/**
 * Check if paramater has value
 */
if(!empty($image)){
    
  
    /**
     * Try by copy
     */
    $copyImage = (new \Modules\Renders\ImageHandler($image))->getCopy();
    if(!empty($copyImage)){
        transFerImage($copyImage);
    }


    /**
     * load image and read it content
     */
    $imageFound = (new \Modules\Renders\ImageHandler($image))->loadImage();
    
     
    if(!$imageFound->isError()){
        /**
         * read to send image
         */
      

       // $imageFound->setCopy($imageFound);
        transFerImage(['extension'=>$imageFound->getExtension(), 'path'=>$imageFound->getPath()]);
        unset($imageFound);
    }
}else{
    /**
     * show default no image exist
     */
}

/**
 * @param array $image
 * @return void
 */
function transFerImage(array $image): void
{
    $expires = 60 * 60 * 24 * 7; // Cache for one week (in seconds)
    header("Cache-Control: max-age=$expires");
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($image['path'])) . ' GMT');
    header('ETag: ' . md5(filemtime($image['path'])));
    header("Content-Type: image/{$image['extension']}");
      
    readfile($image['path']);
    exit;
}
