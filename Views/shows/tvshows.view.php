<?php

use Datainterface\Query;
use GlobalsFunctions\Globals;
use Modules\Shows\ShowsHandlers;

$tvShows = (new ShowsHandlers())->shows();

if(isset($fromIndex) && $fromIndex === true){
    $limit = \functions\config("PAGERLIMIT");
    $tvShows = Query::query("SELECT * FROM tv_shows ORDER BY show_changed DESC LIMIT $limit");
}else{
    \Core\Router::attachView('tags',['title'=> 'Tv Shows']);
}

$render = new \Modules\Renders\RenderHandler($tvShows);
$tvShows = $render->getOutPutRender();
$here = Globals::url();


?>
<section class="w-100 mt-lg-2 container-container">
    <div class="d-inline-flex <?php echo str_contains($here, 'index') ? 'm-m' : ''; ?>">
        <div class="row m-auto justify-content-center my-movies"><?php if(!empty($tvShows)):?><?php foreach ($tvShows as $key=>$value): ?>
                <div class="card bg-dark mx-1 mt-3 my-card">
                <a href="view-tv-show?show=<?php echo $value['show_uuid'] ?? null; ?>"><img src="<?php echo $value['show_image'] ?? null; ?>" class="card-img-top m-auto zoom" alt="<?php echo $value['title'] ?? null; ?>"></a>
                <div class="card-body">
                    <p class="card-text text-white-50"><a href="view-tv-show?show=<?php echo $value['show_uuid'] ?? null; ?>" class="text-decoration-none text-white-50"><?php echo substr($value['title'], 0, 15).'..' ?? null; ?></a></p>
                    <p class="card-text text-white-50"><?php echo (new \DateTime($value['release_date']))->format('M d, Y') ?? null; ?></p>
                </div>
                </div><?php endforeach; ?><?php endif; ?>
        </div>
    </div>
    <?php Modules\Renders\RenderHandler::pager($render); ?>
</section>