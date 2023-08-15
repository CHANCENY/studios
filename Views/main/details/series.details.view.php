<?php

use GlobalsFunctions\Globals;

if(empty(Globals::get('series-id'))){
    Globals::redirect(Globals::home());
    exit;
}


$show = (new \Modules\Modals\Details(Globals::get("series-id")))->load("shows");

$moreImages = $show->getMorePhotos();
$reviews = $show->reviews();
$youMayLike = $show->getYouMayLike();
$collectionAll = $show->getShowsInfo($show->id());

$videos = $show->getVideoTrailers();
$ids = [];
foreach ($videos as $key=>$value){
    $l = explode('=', $value);
    $ids[] = end($l);
}
?>
<!-- details -->
<section class="section details">
    <!-- details background -->
    <div class="details__bg" data-bg="assets/main/img/home/home__bg.jpg"></div>
    <!-- end details background -->

    <!-- details content -->
    <div class="container">
        <div class="row">
            <!-- title -->
            <div class="col-12">
                <h1 class="details__title"><?php echo $show->getTitle(); ?></h1>
            </div>
            <!-- end title -->

            <!-- content -->
            <div class="col-10">
                <div class="card card--details card--series">
                    <div class="row">
                        <!-- card cover -->
                        <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                            <div class="card__cover">
                                <img src="<?php echo $show->getImage(); ?>" alt="<?php echo $show->getTitle(); ?>">
                            </div>
                        </div>
                        <!-- end card cover -->

                        <!-- card content -->
                        <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-xl-9">
                            <div class="card__content">
                                <div class="card__wrap">
                                    <span class="card__rate"><i class="icon ion-ios-star"></i><?php echo $show->getRating(); ?></span>

                                    <ul class="card__list">
                                        <li>HD</li>
                                        <li>16+</li>
                                    </ul>
                                </div>

                                <ul class="card__meta">
                                    <li><span>Genre:</span><?php $genre = $show->getGenresRenderble(); foreach ($genre as $key=>$vlaue): ?>
                                        <a href="<?php echo $vlaue['link'] ?? null; ?>" title="<?php echo $vlaue['title'] ?? null; ?>" rel="nofollow"><?php echo $vlaue['text'] ?? null; ?></a>
                                    <?php endforeach; ?></li>
                                    <li><span>Release year:</span><?php echo $show->getReleaseDate()->format("M d, Y"); ?></li>
                                    <li><span>Running time:</span> <?php echo $show->getDuration(); ?> min</li>
                                    <li><span>Country:</span><?php $countries = $show->countryRenderable(); foreach ($countries as $key=>$value): ?>
                                        <a href="<?php echo $value['link'] ?? null; ?>" title="<?php echo $value['title'] ?? null; ?>" rel="nofollow"><?php echo $value['text'] ?? null; ?></a>
                                    <?php endforeach; ?></li>
                                </ul>

                                <div class="card__description card__description--details"><?php echo $show->getOverview(); ?>
                                </div>
                            </div>
                        </div>
                        <!-- end card content -->
                    </div>
                </div>
            </div>
            <!-- end content -->

            <!-- player -->
            <div class="col-12 col-xl-6">
                <iframe width="540" height="300" src="https://www.youtube.com/embed/<?php echo $ids[random_int(0, count($ids)-1)]; ?>?rel=0&modestbranding=1&autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <!-- end player -->

            <!-- accordion -->
            <div class="col-12 col-xl-6"><?php if (!empty($collectionAll)): ?>
                <div class="accordion" id="accordion"><?php foreach ($collectionAll as $key=>$value): ?>
                    <div class="accordion__card">
                        <div class="card-header" id="<?php str_replace(' ','-',$value['season_name']); ?>">
                            <button type="button" data-toggle="collapse" data-target="#collapse<?php echo $value['season_id']; ?>" aria-expanded="true" aria-controls="collapseOne">
                                <span>Season: <?php echo $value['season_number'];  ?></span>
                                <span><?php echo $value['episode_count']; ?> Episodes from <?php echo (new DateTime($value['air_date']))->format("M, Y"); ?></span>
                            </button>
                        </div>

                        <div id="collapse<?php echo $value['season_id']; ?>" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <table class="accordion__list">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Air Date</th>
                                    </tr>
                                    </thead>

                                    <tbody><?php $episodes = $value['episodes_found']; foreach ($episodes as $e=>$episode): ?>
                                    <tr>
                                        <th><?php echo $episode['epso_number'] ?? null; ?></th>
                                        <td><a href="<?php echo (new \Modules\Modals\Playing($show))->episodeLink($episode['episode_uuid']); ?>"><?php echo $episode['title'] ?? null; ?></a></td>
                                        <td><?php echo (new DateTime($episode['air_date']))->format("l, F jS, Y"); ?></td>
                                    </tr>
                                    <?php endforeach; ?></tbody>
                                </table>
                            </div>
                        </div>
                    </div><?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?></div>
            <!-- end accordion -->

            <div class="col-12">
                <div class="details__wrap">
                    <!-- availables -->
                    <div class="details__devices">
                        <span class="details__devices-title">Available on devices:</span>
                        <ul class="details__devices-list">
                            <li><i class="icon ion-logo-apple"></i><span>IOS</span></li>
                            <li><i class="icon ion-logo-android"></i><span>Android</span></li>
                            <li><i class="icon ion-logo-windows"></i><span>Windows</span></li>
                            <li><i class="icon ion-md-tv"></i><span>Smart TV</span></li>
                        </ul>
                    </div>
                    <!-- end availables -->

                    <!-- share -->
                    <div class="details__share">
                        <span class="details__share-title">Share with friends:</span>

                        <ul class="details__share-list">
                            <li class="facebook"><a href="#"><i class="icon ion-logo-facebook"></i></a></li>
                            <li class="instagram"><a href="#"><i class="icon ion-logo-instagram"></i></a></li>
                            <li class="twitter"><a href="#"><i class="icon ion-logo-twitter"></i></a></li>
                            <li class="vk"><a href="#"><i class="icon ion-logo-vk"></i></a></li>
                        </ul>
                    </div>
                    <!-- end share -->
                </div>
            </div>
        </div>
    </div>
    <!-- end details content -->
</section>
<!-- end details -->

<!-- content -->
<section class="content">
    <div class="content__head">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- content title -->
                    <h2 class="content__title">Discover</h2>
                    <!-- end content title -->

                    <!-- content tabs nav -->
                    <ul class="nav nav-tabs content__tabs" id="content__tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Comments</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Reviews</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">Photos</a>
                        </li>
                    </ul>
                    <!-- end content tabs nav -->

                    <!-- content mobile tabs nav -->
                    <div class="content__mobile-tabs" id="content__mobile-tabs">
                        <div class="content__mobile-tabs-btn dropdown-toggle" role="navigation" id="mobile-tabs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <input type="button" value="Comments">
                            <span></span>
                        </div>

                        <div class="content__mobile-tabs-menu dropdown-menu" aria-labelledby="mobile-tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Comments</a></li>

                                <li class="nav-item"><a class="nav-link" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Reviews</a></li>

                                <li class="nav-item"><a class="nav-link" id="3-tab" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">Photos</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- end content mobile tabs nav -->
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8 col-xl-8">
                <!-- content tabs -->
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
                        <div class="row"><?php \Core\Router::attachView("ccccccccccccccccccccccccccccccccccccc",['b'=>"shows", 'e'=>$show->id()]); ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
                        <div class="row">
                            <!-- reviews -->
                            <div class="col-12">
                                <div class="reviews">
                                    <ul class="reviews__list"><?php if(!empty($reviews)): foreach ($reviews as $key=>$value): ?>
                                            <li class="reviews__item">
                                            <div class="reviews__autor">
                                                <img class="reviews__avatar" src="<?php echo $value['author_details']['avatar_path'] ?? 'assets/main/img/user.png'; ?>" alt="">
                                                <span class="reviews__name"><?php echo $value['author'] ?? null; ?></span>
                                                <span class="reviews__time"><?php echo (new DateTime($value['created_at']))->format("M d, Y") ?? null; ?> by <?php echo $value['author_details']['username'] ?? null;  ?></span>

                                                <span class="reviews__rating"><i class="icon ion-ios-star"></i><?php echo number_format($value['author_details']['rating'] ?? 0.0, 1) ?? 0.0; ?></span>
                                            </div>
                                            <p class="reviews__text"><?php echo $value['content'] ?? null; ?></p>
                                            </li><?php endforeach; endif; ?>
                                    </ul>

                                    <form action="#" class="form">
                                        <input type="text" class="form__input" placeholder="Title">
                                        <textarea class="form__textarea" placeholder="Review"></textarea>
                                        <div class="form__slider">
                                            <div class="form__slider-rating" id="slider__rating"></div>
                                            <div class="form__slider-value" id="form__slider-value"></div>
                                        </div>
                                        <button type="button" class="form__btn">Send</button>
                                    </form>
                                </div>
                            </div>
                            <!-- end reviews -->
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="3-tab">
                        <!-- project gallery -->
                        <div class="gallery" itemscope>
                            <div class="row"><?php if(!empty($moreImages)): foreach ($moreImages as $key=>$value): ?>

                                    <!-- gallery item -->
                                    <figure class="col-12 col-sm-6 col-xl-4" itemprop="associatedMedia" itemscope>
                                        <a href="https://image.tmdb.org/t/p/w500/<?php echo $value['file_path'] ?? null; ?>" itemprop="contentUrl" data-size="1920x1280">
                                            <img src="https://image.tmdb.org/t/p/w185/<?php echo $value['file_path'] ?? null; ?>" itemprop="thumbnail" alt="<?php echo $value['iso_639_1'] ?? null; ?>" />
                                        </a>
                                        <figcaption itemprop="caption description">Some image caption 1</figcaption>
                                    </figure>
                                    <!-- end gallery item -->

                                <?php endforeach; endif; ?></div>
                        </div>
                        <!-- end project gallery -->
                    </div>
                </div>
                <!-- end content tabs -->
            </div>

            <!-- sidebar -->
            <div class="col-12 col-lg-4 col-xl-4">
                <div class="row">
                    <!-- section title -->
                    <div class="col-12">
                        <h2 class="section__title section__title--sidebar">You may also like...</h2>
                    </div><?php if($youMayLike): foreach ($youMayLike as $key=>$value): ?>
                        <!-- end section title -->

                        <!-- card -->
                        <div class="col-6 col-sm-4 col-lg-6">
                            <div class="card">
                                <div class="card__cover">
                                    <img src="<?php echo $value['image'] ?? null; ?>" alt="<?php echo $value['title'] ?? null; ?>">
                                    <a href="<?php echo \Modules\Modals\Home::buildLinkFor($value['bundle'], $value['uuid']) ?? null; ?>" class="card__play">
                                        <i class="icon ion-ios-play"></i>
                                    </a>
                                </div>
                                <div class="card__content">
                                    <h3 class="card__title"><a href="<?php echo \Modules\Modals\Home::buildLinkFor($value['bundle'], $value['uuid']) ?? null; ?>"><?php echo $value['title'] ?? null; ?></a></h3>
                                    <span class="card__category"><?php $genre = \Modules\Modals\Home::buildGenre($value['genre']); ?>
                                        <?php foreach ($genre as $k=>$v): ?>
                                            <a href="<?php echo $v['link'] ?? null; ?>" title="<?php echo $v['title'] ?? null; ?>"><?php echo $v['text'] ?? null; ?></a>
                                        <?php endforeach; ?>
                                </span>
                                    <span class="card__rate"><i class="icon ion-ios-star"></i><?php echo number_format($value['rating'] ?? 0.0, 1) ?? 0.0;  ?></span>
                                </div>
                            </div>
                        </div>
                        <!-- end card -->

                    <?php endforeach; endif; ?></div>
            </div>
            <!-- end sidebar -->
        </div>
    </div>
</section>
<!-- end content -->


<?php
\Core\Router::attachView("pppppppppppppppp");
?>