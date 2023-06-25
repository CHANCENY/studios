<?php

use GlobalsFunctions\Globals;
use Modules\Shows\ShowsHandlers;

$showEpisode = [];
$movie = [];
$genreName = "";
if(!empty(Globals::get('w'))){
    /**
     * bring show season episode
     */
    $showEpisode = (new ShowsHandlers())->getEpisode(Globals::get('w'))[0] ?? [];
    $genreName = (new ShowsHandlers())->getGenre($showEpisode['type']);
}else{
    /**
     * bring movie
     */
    $movie = (new \Modules\Movies\Movie())->getMovie(Globals::get('m'))[0] ?? [];
}

Globals::redirect($movie['url'] ?? $showEpisode['url']);

?>
