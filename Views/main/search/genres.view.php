<?php


use GlobalsFunctions\Globals;
use Modules\Modals\Debug;
use Modules\Modals\Home;
use Modules\Modals\Searches;



if(empty(Globals::get('genre')) || empty(Globals::get('type'))){
    Globals::redirect('home');
}
$genre = Globals::get('genre');
$type = Globals::get('type');
$moviesLists = (new Searches($genre, $type))->searchByGenres();

?>
<!-- page title -->
<section class="section section--first section--bg" data-bg="img/section/section.jpg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section__wrap">
                    <!-- section title -->
                    <h2 class="section__title">Search results</h2>
                    <!-- end section title -->

                    <!-- breadcrumb -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb__item"><a href="home">Home</a></li>
                        <li class="breadcrumb__item breadcrumb__item--active">Search results</li>
                    </ul>
                    <!-- end breadcrumb -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end page title -->
<?php \Core\Router::attachView("ffffffffffffffffffffffff"); ?>

<?php if($type === 'movies'): ?>
    <!-- catalog -->
    <div class="catalog">
        <div class="container">

            <div class="row"><?php if(!empty($moviesLists)): foreach ($moviesLists as $key=>$movie): ?>

                    <!-- card --><div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                    <div class="card">
                        <div class="card__cover">
                            <img src="<?php echo $movie['image'] ?? null; ?>" alt="<?php echo $movie['title'] ?? null; ?>">
                            <a href="<?php echo Home::buildLinkFor($movie['bundle'], $movie['uuid']); ?>" title="<?php echo $movie['title'] ?? null; ?>" rel="index" class="card__play">
                                <i class="icon ion-ios-play"></i>
                            </a>
                        </div>
                        <div class="card__content">
                            <h3 class="card__title"><a href="<?php echo Home::buildLinkFor($movie['bundle'], $movie['uuid']); ?>"><?php echo $movie['title'] ?? null; ?></a></h3>
                            <span class="card__category"><?php $genre = Home::buildGenre($movie['genre'], $movie['bundle']); ?>
                                <?php foreach ($genre as $k=>$value): ?>
                                    <a href="<?php echo $value['link'] ?? null; ?>" title="<?php echo $value['title'] ?? null; ?>" rel="nofollow"><?php echo $value['text'] ?? null; ?></a>
                                <?php endforeach; ?>
                        </span>
                            <span class="card__rate"><i class="icon ion-ios-star"></i><?php echo number_format($movie['rating'] ?? 0.0, 1) ?? 0.0; ?></span>
                        </div>
                    </div>
                    </div><?php endforeach; endif; ?>
                <!-- end card -->
            </div>
        </div>
    </div>
    <!-- end catalog -->
<?php elseif ($type === 'shows'): ?>
    <!-- catalog -->
    <div class="catalog">
        <div class="container">

            <div class="row"><?php if(!empty($moviesLists)): foreach ($moviesLists as $key=>$show): ?>

                    <!-- card --><div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                    <div class="card">
                        <div class="card__cover">
                            <img src="<?php echo $show['image'] ?? null; ?>" alt="<?php echo $show['title'] ?? null; ?>">
                            <a href="<?php echo Home::buildLinkFor($show['bundle'], $show['uuid']); ?>" title="<?php echo $show['title'] ?? null; ?>" rel="index" class="card__play">
                                <i class="icon ion-ios-play"></i>
                            </a>
                        </div>
                        <div class="card__content">
                            <h3 class="card__title"><a href="<?php echo Home::buildLinkFor($show['bundle'], $show['uuid']); ?>"><?php echo $show['title'] ?? null; ?></a></h3>
                            <span class="card__category"><?php $genre = Home::buildGenre($show['genre'], $show['bundle']); ?>
                                <?php foreach ($genre as $k=>$value): ?>
                                    <a href="<?php echo $value['link'] ?? null; ?>" title="<?php echo $value['title'] ?? null; ?>" rel="nofollow"><?php echo $value['text'] ?? null; ?></a>
                                <?php endforeach; ?>
                        </span>
                            <span class="card__rate"><i class="icon ion-ios-star"></i><?php echo number_format($show['rating'] ?? 0.0, 1) ?? 0.0; ?></span>
                        </div>
                    </div>
                    </div><?php endforeach; endif; ?>
                <!-- end card -->
            </div>
        </div>
    </div>
    <!-- end catalog -->
<?php endif; ?>
