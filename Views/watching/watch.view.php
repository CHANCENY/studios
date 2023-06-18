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
}

?>
<section class="w-100 mt-lg-5">
    <div id="watch" data="<?php echo $showEpisode['title'] ?? null; ?>" class="container w-100">
        <div class="m-auto text-center">
            <div class="embed-responsive embed-responsive-1by1 m-auto">
                <iframe class="embed-responsive-item" src="<?php echo $showEpisode['url'] ?? null; ?>" width="800" height="600" allowfullscreen allowtransparency allow="autoplay" scrolling="no" frameborder="0" loading="lazy"></iframe>
            </div>
        </div>
    </div>
    <div class="mt-5">
        <div class="m-auto text-center text-white-50">
            <h2 class="lead"><?php echo $showEpisode['title'] ?? null; ?></h2><?php if(!empty(Globals::get('m'))): ?>
            <p>my movie description and more</p><?php endif; ?>
            <p>duration <?php echo $showEpisode['duration'].' | '.$genreName; ?></p>
        </div>
    </div>
</section>

<div>
    <script src="assets/my-styles/js/watch.js"></script>
</div>