<?php


?>
<!-- page title -->
<section class="section section--first section--bg" data-bg="img/section/section.jpg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section__wrap">
                    <!-- section title -->
                    <h2 class="section__title">Catalog list</h2>
                    <!-- end section title -->

                    <!-- breadcrumb -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb__item"><a href="home">Home</a></li>
                        <li class="breadcrumb__item breadcrumb__item--active">Catalog list</li>
                    </ul>
                    <!-- end breadcrumb -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end page title -->

<?php \Core\Router::attachView("ffffffffffffffffffffffff");
use Modules\Modals\Home;

$showLists = \Modules\Modals\Catalogues::catalogueShowsListing();
?>

<!-- catalog -->
<div class="catalog">
    <div class="container">
        <div class="row"><?php if(!empty($showLists)): foreach ($showLists as $key=>$movie): ?>
                <!-- card -->
                <div class="col-6 col-sm-12 col-lg-6">
                <div class="card card--list">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="card__cover">
                                <img src="<?php echo $movie['image'] ?? null; ?>" alt="<?php echo $movie['title'] ?? null; ?>">
                                <a href="<?php echo Home::buildLinkFor($movie['bundle'], $movie['uuid']); ?>" title="<?php echo $movie['title'] ?? null; ?>" class="card__play">
                                    <i class="icon ion-ios-play"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-12 col-sm-8">
                            <div class="card__content">
                                <h3 class="card__title"><a href="<?php echo Home::buildLinkFor($movie['bundle'], $movie['uuid']); ?>" alt="<?php echo $movie['title'] ?? null; ?>"><?php echo $movie['title'] ?? null; ?></a></h3>
                                <span class="card__category"><?php $genre = Home::buildGenre($movie['genre']); ?>
                                    <?php foreach ($genre as $k=>$value): ?>
                                        <a href="<?php echo $value['link'] ?? null; ?>" title="<?php echo $value['title'] ?? null; ?>" rel="nofollow"><?php echo $value['text'] ?? null; ?></a>
                                    <?php endforeach; ?>
                                </span>

                                <div class="card__wrap">
                                    <span class="card__rate"><i class="icon ion-ios-star"></i><?php echo number_format($movie['rating'] ?? 0.0, 1) ?? 0.0; ?></span>

                                    <ul class="card__list">
                                        <li>HD</li>
                                        <li>16+</li>
                                    </ul>
                                </div>

                                <div class="card__description">
                                    <p><?php echo $movie['overview'] ?? null; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div><?php endforeach; endif; ?>
            <!-- end card -->

            <!-- paginator -->
            <div class="col-12">
                <ul class="paginator paginator--list">
                    <li class="paginator__item paginator__item--prev">
                        <a href="#"><i class="icon ion-ios-arrow-back"></i></a>
                    </li>
                    <li class="paginator__item"><a href="#">1</a></li>
                    <li class="paginator__item paginator__item--active"><a href="#">2</a></li>
                    <li class="paginator__item"><a href="#">3</a></li>
                    <li class="paginator__item"><a href="#">4</a></li>
                    <li class="paginator__item paginator__item--next">
                        <a href="#"><i class="icon ion-ios-arrow-forward"></i></a>
                    </li>
                </ul>
            </div>
            <!-- end paginator -->
        </div>
    </div>
</div>
<!-- end catalog -->

<?php

/**
 * This is premiers
 */
\Core\Router::attachView("pppppppppppppppp");
?>
