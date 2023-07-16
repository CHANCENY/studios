<?php
namespace Crons;
use Alerts\Alerts;

class Cron
{
    private array $result;

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }
    public function __construct(private readonly \Modules\Imports\ImageCreation $imageCreator)
    {
    }

    public function runCron(): Cron
    {
        $imageList = $this->imageCreator->unProcessedImages();
        foreach ($imageList as $key=>$value){
            $this->result[] = $this->imageCreator->processImages($value['table'], $value['column'], $value['data']);
        }
        return $this;
    }
}

$flag = (new Cron((new \Modules\Imports\ImageCreation())))->runCron()->getResult();
$totalTrue = array_filter($flag);
if(count($totalTrue) === 3){
    (new \Modules\Imports\ImageCreation())->records(\Modules\Imports\ImageCreation::class, 1,
        "Cron of Image Creation Successfully Run with total thread (".count($totalTrue).")");
    echo Alerts::alert('info',"Cron of Image Creation Successfully Run with total thread (".count($totalTrue).")");
}else{
    (new \Modules\Imports\ImageCreation())->records(\Modules\Imports\ImageCreation::class, 2,
        "Cron of image Creation run with result thread (".count($totalTrue).")");
    echo Alerts::alert('warning', "Cron of image Creation run with result thread (".count($totalTrue).")");
}