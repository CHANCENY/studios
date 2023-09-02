<?php

namespace Modules\Imports;


use GlobalsFunctions\Globals;
use Modules\Modals\Debug;
use function functions\config;

class RemoveFiles
{
    private array $files;

    public function __construct(private array $directories = [])
    {
        $this->loadFiles();
        $this->deleteFiles();
    }

    private function loadFiles(): void
    {
        $this->files = [];
        foreach ($this->directories as $key=>$directory){
            $content = scandir($directory);
            if($content !== false){
                foreach ($content as $k=>$value){
                      $file = "$directory/$value";
                      if(is_file($file) && file_exists($file)){
                          $info = (new \SplFileInfo($file));
                          $list = explode(',', config('DETETABLE_FILES'));
                          if(in_array($info->getExtension(), $list)){
                              $this->files[] = $file;
                          }
                      }
                }
            }
        }
    }

    public function deleteFiles(): bool
    {
        $flag = [];
       if(isset($this->files)){
           foreach ($this->files as $key=>$file){
               if(file_exists($file) && !str_contains($file, 'logo')){
                   if(unlink($file)){
                       $flag[] = true;
                   }
               }
           }
       }
       return count($this->files) === count($flag);
    }
}