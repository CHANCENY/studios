<?php
namespace Crons;
use Alerts\Alerts;
use Modules\Imports\ImageCreation;

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
    public function __construct(private readonly ImageCreation $imageCreator)
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

$flag = (new Cron((new ImageCreation())))->runCron()->getResult();
$totalTrue = array_filter($flag);
var_dump($totalTrue);
if(count($totalTrue) === 3){
    (new ImageCreation())->records(ImageCreation::class, 1,
        "Cron of Image Creation Successfully Run with total thread (".count($totalTrue).")");
    echo Alerts::alert('info',"Cron of Image Creation Successfully Run with total thread (".count($totalTrue).")");
}else{
    (new ImageCreation())->records(ImageCreation::class, 2,
        "Cron of image Creation run with result thread (".count($totalTrue).")");
    echo Alerts::alert('warning', "Cron of image Creation run with result thread (".count($totalTrue).")");
}


/**
 * cron @/home/u599963710/domains/quickapistorage.com/public_html/stream/crons/imagecreation.php
 */