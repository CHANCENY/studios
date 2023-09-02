<?php

use Modules\Modals\Home;
use Modules\Renders\ImageHandler;

?>

    <!-- page title -->
    <section class="section section--first section--bg" data-bg="assets/main/img/section/section.jpg">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__wrap">
                        <!-- section title -->
                        <h2 class="section__title">Catalog grid</h2>
                        <!-- end section title -->

                        <!-- breadcrumb -->
                        <ul class="breadcrumb">
                            <li class="breadcrumb__item"><a href="home">Home</a></li>
                            <li class="breadcrumb__item breadcrumb__item--active">Catalog grid</li>
                        </ul>
                        <!-- end breadcrumb -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end page title -->

<?php

/**
 * This is filter component
 */

\Core\Router::attachView("ffffffffffffffffffffffff");

$data = \Modules\Modals\Catalogues::catalogueShowsGrid();
$showsLists = $data['data'];
?>

    <!-- catalog -->
    <div class="catalog">
        <div class="container">

            <div class="row"><?php if(!empty($showsLists)): foreach ($showsLists as $key=>$show): ?>

                    <!-- card --><div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                    <div class="card">
                        <div class="card__cover">
                            <img src="<?php echo ImageHandler::image($show['image']) ?? null; ?>" alt="<?php echo $show['title'] ?? null; ?>">
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

                <!-- paginator -->
                <div class="col-12">
                    <ul class="paginator">
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