<?php

namespace Modules\Renders;

use GlobalsFunctions\Globals;

class RenderHandler
{
    private array $dataRender;

    /**
     * @return array
     */
    public function getDataRender(): array
    {
        return $this->dataRender;
    }

    /**
     * @return mixed
     */
    public function getOutPutRender(): mixed
    {
        return $this->outPutRender;
    }

    /**
     * @return mixed
     */
    public function getPositions()
    {
        return $this->positions;
    }
    private mixed $outPutRender;
    private $positions;

    public function __construct(array $data)
    {
         $this->dataRender = $data;
         $this->preRender();
    }

    public function preRender(): void
    {
        $chunkedArray = array_chunk($this->dataRender, 10);
        $position = Globals::get('page');
        if(empty($position)){
            $this->outPutRender =  $chunkedArray[0] ?? [];
        }else{
            if(isset($chunkedArray[intval($position)])){
                $this->outPutRender = $chunkedArray[intval($position)] ??  $chunkedArray[0];
            }else{
                 Globals::redirect(Globals::url());
            }
            
        }
        $this->positions = array_keys($chunkedArray);
    }

    public function positions(): array
    {
        return $this->positions;
    }
}