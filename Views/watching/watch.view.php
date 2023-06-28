<?php

use GlobalsFunctions\Globals;
use Modules\Shows\ShowsHandlers;

$showEpisode = [];
$movie = [];
if(!empty(Globals::get('w'))){
    /**
     * bring show season episode
     */
    $showEpisode = (new ShowsHandlers())->getEpisode(Globals::get('w'))[0] ?? [];
}else{
    /**
     * bring movie
     */
    $movie = (new \Modules\Movies\Movie())->getMovie(Globals::get('m'))[0] ?? [];
}

if(!empty($showEpisode['url'])){
    Globals::redirect($movie['url'] ?? $showEpisode['url']);
}else{
    $result = "<h1>Link of Show/Episode or Movie not Found</h1><p>You can request this episode or movie by visit Request page.
               Search show or movie name then if it tv show please state season and episode you are looking for in text box
               provide on page.<a href='request-show-movie'>Visit Request page.</a> Upon request you will be notified within 4hrs
                with link of show or movie you requested. Thank you</p>";
}
?>
<section class="container mt-lg-5">
    <div class="m-auto text-center"><?php echo $result; ?>
    </div>
</section>
