<?php
use Modules\Modals\Home;
use Modules\Renders\ImageHandler;

/**
 * This is for new this season
 */
$newThisSeason = Home::newThisSeasonRandomised();

?>
<!-- home -->
<section class="home">
    <!-- home bg -->
    <div class="owl-carousel home__bg">
        <div class="item home__cover" data-bg="assets/main/img/home/home__bg.jpg"></div>
        <div class="item home__cover" data-bg="assets/main/img/home/home__bg2.jpg"></div>
        <div class="item home__cover" data-bg="assets/main/img/home/home__bg3.jpg"></div>
        <div class="item home__cover" data-bg="assets/main/img/home/home__bg4.jpg"></div>
    </div>
    <!-- end home bg -->

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="home__title"><b>NEW ITEMS</b> OF THIS SEASON</h1>

                <button class="home__nav home__nav--prev" type="button">
                    <i class="icon ion-ios-arrow-round-back"></i>
                </button>
                <button class="home__nav home__nav--next" type="button">
                    <i class="icon ion-ios-arrow-round-forward"></i>
                </button>
            </div>

            <div class="col-12">
                <div class="owl-carousel home__carousel"><?php if(!empty($newThisSeason)): ?><?php foreach ($newThisSeason as $key=>$newThis): ?>
                        <div class="item">
                        <!-- card -->
                        <div class="card card--big">
                            <div class="card__cover">
                                <img src="<?php echo ImageHandler::image($newThis['image']) ?? null; ?>" alt="<?php echo $newThis['title'] ?? null; ?>">
                                <a href="<?php echo Home::buildLinkFor($newThis['bundle'], $newThis['uuid']) ?? null; ?>" rel="index" title="<?php echo $newThis['title'] ?? null; ?>" class="card__play">
                                    <i class="icon ion-ios-play"></i>
                                </a>
                            </div>
                            <div class="card__content">
                                <h3 class="card__title"><a href="<?php echo Home::buildLinkFor($newThis['bundle'], $newThis['uuid']) ?? null; ?>" rel="index"><?php echo $newThis['title'] ?? null; ?></a></h3>
                                <span class="card__category"><?php $genre = Home::buildGenre($newThis['genre'] ?? "Unknown", $newThis['bundle']); ?>
										<?php foreach ($genre as $k=>$value): ?>
										   <a href="<?php echo $value['link'] ?? null; ?>" rel="nofollow" title="<?php echo $value['title'] ?? null; ?>"><?php echo $value['text'] ?? null; ?></a>
										<?php endforeach; ?>
									</span>
                                <span class="card__rate"><i class="icon ion-ios-star"></i><?php echo number_format($newThis['rating'] ?? 0.0, 1) ?? 0.0; ?></span>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                <?php endforeach; endif; ?></div>
            </div>
        </div>
    </div>
</section>
<!-- end home -->
<?php

/**
 * This will bring new release movies and shows
 */

$newReleaseMovies = Home::newReleaseMovies();
$newReleaseShows = Home::newReleaseShows();
$combinationNewRelease = array_merge($newReleaseShows, $newReleaseMovies);


/**
 * This will bring movies at will great vote average
 */
$moviesSelectedHighVotes = Home::moviesHighVotes();
$showsSelectedHighVotes = Home::showsHighVotes();

?>
<!-- content -->
<section class="content">
    <div class="content__head">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- content title -->
                    <h2 class="content__title">New items</h2>
                    <!-- end content title -->

                    <!-- content tabs nav -->
                    <ul class="nav nav-tabs content__tabs" id="content__tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">NEW RELEASES</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">MOVIES</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">TV SERIES</a>
                        </li>

<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" data-toggle="tab" href="#tab-4" role="tab" aria-controls="tab-4" aria-selected="false">CARTOONS</a>-->
<!--                        </li>-->
                    </ul>
                    <!-- end content tabs nav -->

                    <!-- content mobile tabs nav -->
                    <div class="content__mobile-tabs" id="content__mobile-tabs">
                        <div class="content__mobile-tabs-btn dropdown-toggle" role="navigation" id="mobile-tabs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <input type="button" value="New items">
                            <span></span>
                        </div>

                        <div class="content__mobile-tabs-menu dropdown-menu" aria-labelledby="mobile-tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">NEW RELEASES</a></li>

                                <li class="nav-item"><a class="nav-link" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">MOVIES</a></li>

                                <li class="nav-item"><a class="nav-link" id="3-tab" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">TV SERIES</a></li>

<!--                                <li class="nav-item"><a class="nav-link" id="4-tab" data-toggle="tab" href="#tab-4" role="tab" aria-controls="tab-4" aria-selected="false">CARTOONS</a></li>-->
                            </ul>
                        </div>
                    </div>
                    <!-- end content mobile tabs nav -->
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- content tabs -->
        <div class="tab-content" id="myTabContent"><?php if(!empty($newReleaseMovies)): ?>

            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
                <div class="row"><?php foreach($combinationNewRelease as $key=>$newRelease): ?>
                    <!-- card -->
                    <div class="col-6 col-sm-12 col-lg-6">
                        <div class="card card--list">
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="card__cover">
                                        <img src="<?php echo ImageHandler::image($newRelease['image']) ?? null; ?>" alt="<?php echo $newRelease['title'] ?? null; ?>">
                                        <a href="<?php echo Home::buildLinkFor($newRelease['bundle'], $newRelease['uuid']) ?? null; ?>" class="card__play">
                                            <i class="icon ion-ios-play"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-8">
                                    <div class="card__content">
                                        <h3 class="card__title"><a href="<?php echo Home::buildLinkFor($newRelease['bundle'], $newRelease['uuid']) ?? null; ?>"><?php echo $newRelease['title'] ?? null; ?></a></h3>
                                        <span class="card__category"><?php $genre = Home::buildGenre($newRelease['genre'], $newRelease['bundle']); ?>
												<?php foreach ($genre as $k=>$value): ?>
												   <a href="<?php echo $value['link'] ?? null; ?>" title="<?php echo $value['title'] ?? null; ?>" rel="nofollow"><?php echo $value['text'] ?? null; ?></a>
												<?php endforeach; ?>
											</span>

                                        <div class="card__wrap">
                                            <span class="card__rate"><i class="icon ion-ios-star"></i><?php echo number_format($newRelease['rating'] ?? 0.0, 1) ?? 0.0 ?></span>

                                            <ul class="card__list">
                                                <li>HD</li>
                                                <li>16+</li>
                                            </ul>
                                        </div>

                                        <div class="card__description">
                                            <p><?php echo $newRelease['overview'] ?? null; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
                <?php endforeach; ?></div>
            </div><?php endif; ?>

            <?php if(!empty($moviesSelectedHighVotes)): ?><div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
                <div class="row"><?php foreach ($moviesSelectedHighVotes as $key=>$movieHigh): ?>
                    <!-- card -->
                    <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                        <div class="card">
                            <div class="card__cover">
                                <img src="<?php echo ImageHandler::image($movieHigh['image']) ?? null; ?>" alt="<?php echo $movieHigh['title'] ?? null; ?>" rel="index">
                                <a href="<?php echo Home::buildLinkFor($movieHigh['bundle'], $movieHigh['uuid']) ?? null; ?>" title="<?php echo $movieHigh['title'] ?? null; ?>" rel="index" class="card__play">
                                    <i class="icon ion-ios-play"></i>
                                </a>
                            </div>
                            <div class="card__content">
                                <h3 class="card__title"><a href="<?php echo Home::buildLinkFor($movieHigh['bundle'], $movieHigh['uuid']) ?? null; ?>" title="<?php echo $movieHigh['title'] ?? null; ?>" rel="nofollow"><?php echo $movieHigh['title'] ?? null; ?></a></h3>
                                <span class="card__category"><?php $genre = Home::buildGenre($movieHigh['genre'], $movieHigh['bundle']); ?>
										<?php foreach ($genre as $k=>$value): ?>
                                           <a href="<?php echo $value['link'] ?? null; ?>" title="<?php echo $value['title'] ?>" rel="nofollow"><?php echo $value['text'] ?? null; ?></a>
                                        <?php endforeach; ?>
									</span>
                                <span class="card__rate"><i class="icon ion-ios-star"></i><?php echo number_format($movieHigh['rating'] ?? 0.0, 1) ?? 0.0; ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
                <?php endforeach; ?></div>
            </div><?php endif; ?>

            <?php if(!empty($showsSelectedHighVotes)): ?><div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="3-tab">
                <div class="row"><?php foreach ($showsSelectedHighVotes as $key=>$showHigh): ?>
                    <!-- card -->
                    <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                        <div class="card">
                            <div class="card__cover">
                                <img src="<?php echo ImageHandler::image($showHigh['image']) ?? null; ?>" alt="<?php echo $showHigh['title'] ?? null; ?>">
                                <a href="<?php echo Home::buildLinkFor($showHigh['bundle'], $showHigh['uuid']); ?>" rel="index" title="<?php echo $showHigh['title'] ?? null; ?>" class="card__play">
                                    <i class="icon ion-ios-play"></i>
                                </a>
                            </div>
                            <div class="card__content">
                                <h3 class="card__title"><a href="<?php echo Home::buildLinkFor($showHigh['bundle'], $showHigh['uuid']); ?>"><?php echo $showHigh['title'] ?? null; ?></a></h3>
                                <span class="card__category"><?php $genre = Home::buildGenre($showHigh['genre'], $showHigh['bundle']); ?>
										<?php foreach ($genre as $k=>$value): ?>
                                           <a href="<?php echo $value['link'] ?? null; ?>" title="<?php echo $value['title'] ?? null; ?>" rel="nofollow"><?php echo $value['text'] ?? null; ?></a>
                                        <?php endforeach; ?>
									</span>
                                <span class="card__rate"><i class="icon ion-ios-star"></i><?php echo number_format($showHigh['rating'] ?? 0.0, 1) ?? 0.0; ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
                <?php endforeach; ?></div>
            </div><?php endif; ?>

        </div>
        <!-- end content tabs -->
    </div>
</section>
<!-- end content -->

<?php
\Core\Router::attachView("pppppppppppppppp");
?>
<?php
require_once "Views/main/components/patners.php";
?>
