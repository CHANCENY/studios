<?php

namespace routes_handlers;

use Datainterface\Query;
use middle_security\Token;

class MoviesYearly extends Finish
{
    public function __construct(private readonly Token $tokenize)
    {
        $this->handleRequest();
    }

    public function handleRequest(): void
    {
        $currentYear = date('Y');
        $start = (new \DateTime("01-01-$currentYear"))->format('Y-m-d H:i:s');
        $last = (new \DateTime("31-12-$currentYear"))->format('Y-m-d H:i:s');
        $query = "SELECT * FROM movies WHERE created BETWEEN :start_date AND :end_date";
        $thisYear = Query::query($query,['start_date'=>$start, 'end_date'=>$last]);

        $currentYear = strval(intval(date('Y')) - 1);
        $start = (new \DateTime("01-01-$currentYear"))->format('Y-m-d H:i:s');
        $last = (new \DateTime("31-12-$currentYear"))->format('Y-m-d H:i:s');
        $query = "SELECT * FROM movies WHERE created BETWEEN :start_date AND :end_date";
        $lastYear = Query::query($query,['start_date'=>$start, 'end_date'=>$last]);

        $collection = [];
        $collection['totals'] = ['this_year'=> count($thisYear), 'last_year'=>count($lastYear)];

        // Define the old and new values
        $oldValue = count($lastYear);   // Number of movies uploaded last year
        $newValue = count($thisYear);   // Number of movies uploaded so far this year

        // Check if the old value is zero before calculating the percentage difference
        if ($oldValue != 0) {
            // Calculate the percentage difference
            $percentageDifference = (($newValue - $oldValue) / $oldValue) * 100;

        } else {
            // Handle the case where the old value is zero (division by zero)
            $newValue = $newValue <= 0 ? -1000 : $newValue;
            $percentageDifference = ($newValue / 1000) * 100;
        }


        $collection['difference_percentage'] = $percentageDifference."%";
        $this->message = $collection;
        $this->statusCode = 200;

    }
}