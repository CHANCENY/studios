<?php

namespace routes_handlers;

use Datainterface\Query;
use middle_security\Token;

class ShowsLastTenDays extends Finish
{

    protected mixed $message = ['msg'=>"empty"];
    protected int $statusCode = 204;
    public function __construct(private readonly Token $tokenize)
    {
        $this->handleRequest();
    }

    public function handleRequest(): void
    {
        $query = "SELECT * FROM tv_shows WHERE created BETWEEN :start_date AND :end_date";
        $start_date = date('Y-m-d H:i:s', strtotime('-10 days'));
        $end_date = date('Y-m-d H:i:s');
        $data = Query::query($query, ['start_date'=>$start_date, 'end_date'=>$end_date]);
        if(!empty($data)){
            $dates = [];
            foreach ($data as $key=>$value){
                if(gettype($value) === 'array')
                {
                    $line = (new \DateTime($value['created']))->format('Y-m-d');
                    if(isset($dates[$line])){
                        $dates[$line] = intval($dates[$line]) + 1;
                    }else{
                        $dates[$line] = 1;
                    }
                }
            }
            krsort($dates);
            $this->statusCode = 200;
            $this->message =["date_count"=>array_reverse($dates), 'shows'=>array_values($data)];
        }
    }

}