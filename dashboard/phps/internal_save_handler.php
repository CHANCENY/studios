<?php

use GlobalsFunctions\Globals;
use Modules\Imports\ImportHandler;

if(!empty(Globals::get("movie-id")) && !empty(Globals::get("internal")))
{
    $movieDetails = ImportHandler::requestMovie(Globals::get("movie-id"));
    if((new \groups\GroupMovies())->saveAdditionalInfo($movieDetails, Globals::get("internal")))
    {
        Globals::redirect("/movies/listing");
        exit;
    }
}
Globals::redirect("/404");
exit;
