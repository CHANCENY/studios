<?php


?>
<!-- page title -->
<section class="section section--first section--bg" data-bg="assets/main/img/section/section.jpg">
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
use Modules\Renders\ImageHandler;

$data = \Modules\Modals\Catalogues::catalogueLists();
$moviesLists = $data['data'];
?>

<!-- catalog -->
<div class="catalog">
    <div class="container">
        <div class="row"><?php if(!empty($moviesLists)): foreach ($moviesLists as $key=>$movie): ?>
            <!-- card -->
            <div class="col-6 col-sm-12 col-lg-6">
                <div class="card card--list">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="card__cover">
                                <img src="<?php echo ImageHandler::image($movie['image']) ?? null; ?>" alt="<?php echo $movie['title'] ?? null; ?>">
                                <a href="<?php echo Home::buildLinkFor($movie['bundle'], $movie['uuid']); ?>" title="<?php echo $movie['title'] ?? null; ?>" class="card__play">
                                    <i class="icon ion-ios-play"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-12 col-sm-8">
                            <div class="card__content">
                                <h3 class="card__title"><a href="<?php echo Home::buildLinkFor($movie['bundle'], $movie['uuid']); ?>" alt="<?php echo $movie['title'] ?? null; ?>"><?php echo $movie['title'] ?? null; ?></a></h3>
                                <span class="card__category"><?php $genre = Home::buildGenre($movie['genre'], $movie['bundle']); ?>
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
                   <?php echo $data['pager']; ?>
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
