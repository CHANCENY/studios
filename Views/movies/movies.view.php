<?php
use Datainterface\Query;
use GlobalsFunctions\Globals;
use Modules\Shows\ShowsHandlers;

$movies = (new \Modules\Movies\Movie())->movies();
if(isset($fromIndex) && $fromIndex === true){
    $query = "SELECT * FROM movies AS m LEFT JOIN images AS im ON im.target_id = m.movie_id ORDER BY m.movie_id LIMIT 6";
    $movies = \Datainterface\Query::query($query);
}else{
    \Core\Router::attachView('tags',['title'=> 'Movies']);
}
$render = new \Modules\Renders\RenderHandler($movies);
$movies = $render->getOutPutRender();
$here = Globals::url();

?>

<section class="w-100 mt-lg-5 ms-lg-3">
    <div class="d-inline-flex <?php echo str_contains($here, 'index') ? 'm-m' : ''; ?>">
        <div class="row m-auto justify-content-center my-movies"><?php if(!empty($movies)):?><?php foreach ($movies as $key=>$value): ?>
                <div class="card bg-dark mx-1 mt-3" style="width: 12rem;">
                <a href="movie-stream?movie=<?php echo $value['movie_id'] ?? null; ?>"><img src="<?php echo $value['url_image'] ?? null; ?>" class="card-img-top m-auto zoom" alt="<?php echo $value['title'] ?? null; ?>"></a>
                <div class="card-body">
                    <p class="card-text text-white-50"><a href="movie-stream?movie=<?php echo $value['movie_id'] ?? null; ?>" class="text-decoration-none text-white-50"><?php echo substr($value['title'], 0, 15).'..' ?? null; ?></a></p>
                    <p class="card-text text-white-50"><?php echo (new \DateTime($value['release_date']))->format('M d, Y') ?? null; ?></p>
                </div>
                </div><?php endforeach; ?><?php endif; ?>
        </div>
    </div>
    <?php Modules\Renders\RenderHandler::pager($render); ?>
</section>