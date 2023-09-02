<?php

namespace Modules\Renders;

use Core\Router;
use GlobalsFunctions\Globals;
use Json\Json;
use Modules\Imports\RemoveFiles;

class Cache
{
    /**
     * @var true
     */
    private bool $error;
    private string|false $currentPageOutPut;

    public function __construct(private readonly string $pageURL = "home")
    {
    }

    public function setCached($data = null): Cache
    {
        $allowedFiles = [
            'js',
            'css',
            'less',
            'jpg',
            'jpeg',
            'png',
            'webp',
            'mp4',
            'mp3',
            'mkv',
            'scss',
            'otf',
            'txt',
            'json',
            'map'
        ];
        $currentURL = $_SERVER['REQUEST_URI'];
        $pass = false;
        foreach ($allowedFiles as $key=>$value) {
            if (str_ends_with($currentURL, $value)) {
                $pass = true;
                break;
            }
        }

        $ignorePath = ['image-creation','stream-transform-link','tranfering-images-permanent','remove-unsed-files'];
        $currentURL = Globals::url();
        $currentURL = trim($currentURL,'/');
        if(in_array($currentURL, $ignorePath)){
            $pass = true;
        }

        if($pass === true){
            $this->error = true;
            return $this;
        }

        if(!isset($this->pageURL)){
            $this->error = true;
            return $this;
        }

        if(is_null($data)){
            $this->currentPageOutPut = ob_get_contents();
        }else{
            $this->currentPageOutPut = $data;
        }

        $map = "sites/files/cache/map.json";
        $directory2 = "sites/files/cache/tmp/";
        if(!is_dir($directory2)){
            mkdir($directory2,7777,true);
        }
        $list = explode('/', $directory2);
//        $previous = "";
//        foreach ($list as $key=>$dir){
//            $previous = empty($previous) ? $dir : $previous."/$dir";
//            if(chmod($previous,7777)){
//                continue;
//            }
//        }

        if(!file_exists($map)){
            file_put_contents($map, json_encode([]));
        }

        $content = file_get_contents($map);
        $content = Router::clearUrl($content);
        $list = json_decode($content, true);
        $newTMP = $directory2.Json::uuid()."_".Json::uuid().".html";
        $data = [
            'key'=>$this->pageURL,
            'location'=>$newTMP
        ];

        if(file_put_contents($newTMP, $this->currentPageOutPut))
        {
            $list[] = $data;
            if(file_put_contents($map, Router::clearUrl(json_encode($list)))){
                $this->error = false;
            }else{
                $this->error = true;
            }
        }else{
            $this->error = true;
        }
        return $this;
    }

    public function getCached(bool $filepath = true): string
    {
        $map = "sites/files/cache/map.json";
        $directory2 = "sites/files/cache/tmp/";
        if(!is_dir($directory2) || !file_exists($map)){
            return "";
        }

        $content = file_get_contents($map);
        $content = Router::clearUrl($content);
        $list = json_decode($content, true);
        foreach ($list as $key=>$value){
            if(isset($value['key']) && isset($value['location']))
            {
                if($value['key'] === $this->pageURL){
                    if($filepath === false){
                        return file_get_contents($value['location']);
                    }
                    return $value['location'];
                }
            }
        }
        return "";
    }

    public function clearCached(): bool
    {
        $map = "sites/files/cache/map.json";
        $directory2 = "sites/files/cache/tmp/";

        return (new RemoveFiles([$directory2]))->deleteFiles();
    }
}