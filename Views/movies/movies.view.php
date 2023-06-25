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

$position = Globals::get('page');

$render = new \Modules\Renders\RenderHandler($movies);
$movies = $render->getOutPutRender();
$pagination = $render->getPositions();

$previous = 0;
$next = 0;
if(!empty($position)){
    $previous = intval($position) - 1;
    $next = intval($position) + 1;
}

$here = Globals::url();

?>

<section class="w-100 mt-lg-5 ms-lg-3">
    <div class="d-inline-flex <?php echo str_contains($here, 'index') ? 'm-m' : ''; ?>">
        <div class="row m-auto justify-content-center my-movies"><?php if(!empty($movies)):?><?php foreach ($movies as $key=>$value): ?>
                <div class="card bg-dark mx-1 mt-3" style="width: 12rem;">
                <a href="movie-stream?movie=<?php echo $value['movie_id'] ?? null; ?>"><img src="<?php echo $value['url_image'] ?? null; ?>" class="card-img-top m-auto zoom" alt="<?php echo $value['title'] ?? null; ?>"></a>
                <div class="card-body">
                    <p class="card-text text-white-50"><a href="movie-stream?movie=<?php echo $value['movie_id'] ?? null; ?>" class="text-decoration-none text-white-50"><?php echo $value['title'] ?? null; ?></a></p>
                    <p class="card-text text-white-50"><?php echo (new \DateTime($value['release_date']))->format('M d, Y') ?? null; ?></p>
                </div>
                </div><?php endforeach; ?><?php endif; ?>
        </div><?php \Core\Router::attachView('block', ['from'=>Globals::url(), 'list'=>count($movies)]); ?>
    </div>
    <?php if(!empty($pagination) && count($pagination) > 1): ?>
    <nav aria-label="..." class="mt-lg-5 w-100 mb-lg-5">
        <ul class="pagination m-auto">
            <?php if(!empty($position)): ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo Globals::url(); ?>?page=<?php echo strval($previous); ?>">Previous</a>
            </li>
            <?php endif; ?>
            <?php foreach ($pagination as $key=>$page): ?>
                <li class="page-item <?php echo $page == $position ? 'active' : null; ?>">
                    <a class="page-link" href="<?php echo Globals::url(); ?>?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                </li>
            <?php endforeach; ?>
            <?php if(!empty($position)): ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo Globals::url(); ?>?page=<?php echo $next; ?>">Next</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</section>