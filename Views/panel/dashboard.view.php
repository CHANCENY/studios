<?php

use Modules\Episodes\Episode;
use Modules\Movies\Movie;
use Modules\Shows\ShowsHandlers;

$movies = (new Movie())->movies();
$shows = (new ShowsHandlers())->shows();
$episodes = (new Episode())->getEpisodesUnfilter();

$limit =  \functions\config("PAGERLIMIT");
$percent = \functions\config("PERCENT");

$moviesSubs = array_chunk($movies, $limit);
$showSubs = array_chunk($shows, $limit);
$episodeSubs = array_chunk($episodes, $limit);
?>
<div class="container text-center ms-auto text-dark">
    <div class="row">
        <div class="col-4"><?php  \Core\Router::attachView('moderate-view'); ?>
        </div>
        <div class="col-8 border-4">
            <h2 class="text-white-50">Stream studios Dash Board.</h2>
            <p class="text-white-50">Calculation made here are based on items ie movies, shows and episodes per page against PERCENT config</p>
            <div class="mt-lg-4">
                <h4 class="text-white-50 ms-0">Tv shows statistics</h4>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="<?php echo count($showSubs); ?>" aria-valuemin="0" aria-valuemax="<?php echo $percent; ?>">
                    <div class="progress-bar" style="width: <?php echo count($showSubs); ?>%"></div>
                    <span><?php echo count($showSubs).'%'; ?></span>
                </div>
            </div>

            <div class="mt-lg-4">
                <h4 class="text-white-50 ms-0">Movies statistics</h4>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="<?php echo count($moviesSubs) ?? 0; ?>" aria-valuemin="0" aria-valuemax="<?php echo $percent; ?>">
                    <div class="progress-bar text-center" style="width: <?php echo count($moviesSubs) ?? 0; ?>%"></div>
                    <span><?php echo count($moviesSubs).'%'; ?></span>
                </div>
            </div>

            <div class="mt-lg-4">
                <h4 class="text-white-50 ms-0">Episodes statistics</h4>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="<?php echo count($episodeSubs); ?>" aria-valuemin="0" aria-valuemax="<?php echo $percent; ?>">
                    <div class="progress-bar" style="width: <?php echo count($episodeSubs); ?>%"></div>
                    <span><?php echo count($episodeSubs).'%'; ?></span>
                </div>
            </div>
            <div class="mt-lg-4 mb-lg-4"><hr></div><?php \Core\Router::attachView("categories-statistics"); ?>
        </div>
    </div>
</div>