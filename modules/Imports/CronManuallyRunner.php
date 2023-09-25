<?php

namespace Modules\Imports;

class CronManuallyRunner
{
    private array|false $cronFiles;
    /**
     * @var mixed|string
     */
    private mixed $cronToRun;

    public function __construct(private readonly int $cronID = -1)
    {
        $this->cronFiles = array_diff(scandir("crons/"), ['..','.']);
        $this->cronToRun = $this->cronFiles[$this->cronID] ?? "";
        if($this->cronID !== -1){
            $this->running();
        }
    }

    private function running(): void
    {
        $path = "crons/".$this->cronToRun;
        if(file_exists($path)){
            require_once $path;
        }
    }

    public function crons(): array
    {
        $list = array_diff(scandir("crons/"),['..','.']);
        $newListing = [];
        foreach ($list as $key=>$value)
        {
            $line = explode(".", $value);
            $name = $line[0] ?? "NO NAME";
            $name = ucfirst($name);
            $name = str_replace("_", " ", $name);
            $name = str_replace("-", " ", $name);
            $location = $value;
            $newListing[] = ['name'=>$name, 'location'=>$location, 'id'=>$key];
        }
        return $newListing;
    }
}