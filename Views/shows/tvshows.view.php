<?php

use Datainterface\Query;
use GlobalsFunctions\Globals;
use Modules\Shows\ShowsHandlers;

$tvShows = (new ShowsHandlers())->shows();

if(isset($fromIndex) && $fromIndex === true){
    $tvShows = Query::query("SELECT * FROM tv_shows ORDER BY show_id LIMIT 6");
}else{
    \Core\Router::attachView('tags',['title'=> 'Tv Shows']);
}

$render = new \Modules\Renders\RenderHandler($tvShows);
$tvShows = $render->getOutPutRender();
$here = Globals::url();


?>
<section class="w-100 mt-lg-5 ms-lg-3">
    <div class="d-inline-flex <?php echo str_contains($here, 'index') ? 'm-m' : ''; ?>">
        <div class="row m-auto justify-content-center my-movies"><?php if(!empty($tvShows)):?><?php foreach ($tvShows as $key=>$value): ?>
                <div class="card bg-dark mx-1 mt-3" style="width: 12rem;">
                <a href="view-tv-show?show=<?php echo $value['show_id'] ?? null; ?>"><img src="<?php echo $value['show_image'] ?? null; ?>" class="card-img-top m-auto zoom" alt="<?php echo $value['title'] ?? null; ?>"></a>
                <div class="card-body">
                    <p class="card-text text-white-50"><a href="view-tv-show?show=<?php echo $value['show_id'] ?? null; ?>" class="text-decoration-none text-white-50"><?php echo substr($value['title'], 0, 15).'..' ?? null; ?></a></p>
                    <p class="card-text text-white-50"><?php echo (new \DateTime($value['release_date']))->format('M d, Y') ?? null; ?></p>
                </div>
                </div><?php endforeach; ?><?php endif; ?>
        </div><?php \Core\Router::attachView('block', ['from'=>Globals::url(), 'list'=>count($tvShows)]); ?>
    </div>
    <?php Modules\Renders\RenderHandler::pager($render); ?>
</section>