<?php
use Modules\Modals\Home;
/**
 * This is for Expected primers
 */
$expectedList = Home::newPremierMovies();
$chucked = array_chunk($expectedList,6);
$expectedList = $chucked[0];
?>
<!-- expected premiere -->
<section class="section section--bg" data-bg="assets/main/img/section/section.jpg">
    <div class="container">
        <div class="row" id="row-premier">
            <!-- section title -->
            <div class="col-12">
                <h2 class="section__title">Expected premiere</h2>
            </div><?php if(!empty($expectedList)): foreach ($expectedList as $key=>$expected): ?>
                <!-- end section title -->

                <!-- card -->
                <div id="child-box-1" class="col-6 col-sm-4 col-lg-3 col-xl-2">
                <div class="card">
                    <div class="card__cover">
                        <img src="https://image.tmdb.org/t/p/w500<?php echo $expected['poster_path'] ?? $expected['backdrop_path']; ?>" alt="<?php echo $expected['title'] ?? $expected['original_title']; ?>">
                        <a href="expected-premiere?id=<?php echo $expected['id'] ?? null; ?>" class="card__play">
                            <i class="icon ion-ios-play"></i>
                        </a>
                    </div>
                    <div class="card__content">
                        <h3 class="card__title"><a href="expected-premiere?id=<?php echo $expected['id'] ?? null; ?>" rel="index" title="Expected premiere - <?php echo $expected['title'] ?? $expected['original_title']; ?>"><?php echo $expected['title'] ?? $expected['original_title']; ?></a></h3>
                        <span class="card__category"><?php $genre = Home::buildGenre($expected['genre']); ?>
                            <?php  foreach ($genre as $k=>$value): ?>
                                <a href="<?php echo $value['link'] ?? null; ?>" title="<?php echo $value['title'] ?? null; ?>" rel="nofollow"><?php $value['text'] ?? null; ?></a>
                            <?php endforeach; ?>
							</span>
                        <span class="card__rate"><i class="icon ion-ios-star"></i><?php echo number_format($expected['vote_average'] ?? 0.0, 1) ?? 0.0 ?></span>
                    </div>
                </div>
                </div><?php endforeach; endif; ?>
            <!-- end card -->
        </div>
        <!-- section btn -->
        <?php if(count($chucked) > 1): ?><div class="col-12">
            <a id="view-more" class="section__btn">Show more</a>
        </div><?php endif; ?>
        <!-- end section btn -->
    </div>
</section>
<!-- end expected premiere -->
