<?php

use GlobalsFunctions\Globals;

if(Globals::method() === "POST" && !empty(Globals::post('image')) && !empty(Globals::post('name')))
{
    $image = Globals::post('image');
    $path = "../sites/files/profiles/";
    if(!is_dir($path))
    {
        mkdir($path);
    }
    $filename = $path.Globals::post('name');
    $info = new SplFileInfo($filename);
    $last = \Json\Json::uuid().'.'.$info->getExtension();;
    $finalName = $path.$last;
    $image = base64_decode($image);
    if(file_put_contents($finalName, $image)){
        echo \Core\Router::clearUrl(\ApiHandler\ApiHandlerClass::stringfiyData(['link'=> "https://streamstudios.online/sites/files/profiles/$last"]));
    }
    exit;
}