<?php

use Datainterface\Query;
use GlobalsFunctions\Globals;
use Modules\Shows\ShowsHandlers;

$tvShows = (new ShowsHandlers())->shows();

if(isset($fromIndex) && $fromIndex === true){
    $tvShows = Query::query("SELECT * FROM tv_shows ORDER BY show_id LIMIT 4");
}else{
    \Core\Router::attachView('tags',['title'=> 'Tv Shows']);
}

$render = new \Modules\Renders\RenderHandler($tvShows);
$tvShows = $render->getOutPutRender();
$pagination = $render->getPositions();

$position = Globals::get('page');

$previous = 0;
$next = 0;
if(!empty($position)){
    $previous = intval($position) - 1;
    $next = count($tvShows) + 1;
}

?>

<section class="w-100 mt-lg-5 ms-lg-3">
    <div class="row m-auto justify-content-center"><?php foreach ($tvShows as $key=>$show): ?>
        <div class="card bg-dark mx-1 mt-3" style="width: 18rem;">
            <a href="view-tv-show?show=<?php echo $show['show_id'] ?? null; ?>"><img src="<?php echo $show['show_cover_image'] ?? null; ?>" class="card-img-top m-auto zoom" alt="<?php echo $show['title'] ?? null; ?>"></a>
            <div class="card-body">
                <p class="card-text text-white-50"><a href="view-tv-show?show=<?php echo $show['show_id'] ?? null; ?>" class="text-decoration-none text-white-50"><?php echo $show['title'] ?? null; ?></a></p>
                <p class="card-text text-white-50"><?php echo $show['release_date'] ?? null; ?></p>
            </div>
        </div><?php endforeach; ?>
    </div>

    <?php if(!empty($pagination) && count($pagination) > 1): ?>
    <nav aria-label="...">
        <ul class="pagination">
            <?php if(!empty($position)): ?>
            <li class="page-item disabled">
                <a class="page-link" href="<?php echo Globals::uri(); ?>?page=<?php echo $previous; ?>" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            <?php endif; ?>
            <?php foreach ($pagination as $key=>$page): ?>
                <li class="page-item">
                    <a class="page-link <?php echo $page == $position ? 'active' : null; ?>" href="<?php echo Globals::uri(); ?>?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                </li>
            <?php endforeach; ?>
            <?php if(!empty($position)): ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo Globals::uri(); ?>?page=<?php echo $next; ?>">Next</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</section>