<?php

use ApiHandler\ApiHandlerClass;
use Core\Router;
use Datainterface\Insertion;
use GlobalsFunctions\Globals;
use Json\Json;

if(Globals::method() === "POST" && !empty(Globals::post('image')) && !empty(Globals::post('name')))
{
    $image = Globals::post('image');
    $path = "../sites/files/images/shows";
    if(!is_dir($path))
    {
        mkdir($path);
    }
    $filename = $path."/".Globals::post('name');
    $info = new SplFileInfo($filename);
    $last = Json::uuid().'.'.$info->getExtension();;
    $finalName = $path."/".$last;
    $image = base64_decode($image);
    if(file_put_contents($finalName, $image)){
        $info = null;
        $info = new SplFileInfo($finalName);
        $data['image_extension'] = $info->getExtension();
        $data['image_name'] = $info->getFilename();
        $data['image_path'] = $info->getRealPath();
        $data['image_size'] = $info->getSize();
        $data['image_url'] = "streamstudios.online".trim($filename, '.');
        $data['image_uuid'] = Json::uuid();
        Insertion::insertRow("images_managed",$data);
        echo Router::clearUrl(ApiHandlerClass::stringfiyData(['link'=> "https://streamstudios.online/img?image={$data['image_uuid']}"]));
    }
    exit;
}