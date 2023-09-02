<?php use GlobalsFunctions\Globals;
use Modules\Modals\Playing;
use Modules\Renders\ImageHandler;

@session_start();

$data = [];
if(empty(Globals::get('play'))){
    Globals::redirect('home');
    exit;
}

$data['id'] = Globals::get('play');
$data['type'] = Globals::get('type');


if(empty($data)){
    Globals::redirect('home');
    exit;
}

if(empty($data['id']) || empty($data['type'])){
    Globals::redirect('home');
    exit;
}

$thisEntity = new Playing((new \Modules\Modals\Details($data['id'])));
if($data['type'] === "movies"){
    $movie = $thisEntity->getMovie($data['id']);

    if($movie === false){
        Globals::redirect('home');
        exit;
    }
  $thisEntity->load($movie);
}

if($data['type'] === "episode"){
    $show = $thisEntity->getEpisode($data['id']);
    if($show === false){
        Globals::redirect("home");
        exit;
    }
    $thisEntity->load($show);
}
?>
<!-- details -->
<section class="section details">
    <!-- details background -->
    <div class="details__bg" data-bg="/assets/main/img/home/home__bg.jpg"></div>
    <!-- end details background -->

    <!-- details content -->
    <div class="container">
        <div class="row">
            <!-- title -->
            <div class="col-12">
                <h1 class="details__title"><?php echo $thisEntity->getTitle(); ?></h1>
            </div>
            <!-- end title -->

            <!-- content -->
            <div class="col-12 col-xl-6">
                <div class="card card--details">
                    <div class="row">
                        <!-- card cover -->
                        <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-5">
                            <div class="card__cover">
                                <img src="<?php echo ImageHandler::image($thisEntity->getImage()); ?>" alt="">
                            </div>
                        </div>
                        <!-- end card cover -->

                        <!-- card content -->
                        <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-xl-7">
                            <div class="card__content">
                                <div class="card__wrap">
                                    <span class="card__rate"><i class="icon ion-ios-star"></i>Unknown</span>

                                    <ul class="card__list">
                                        <li>HD</li>
                                        <li>16+</li>
                                    </ul>
                                </div>

                                <ul class="card__meta">
                                    <li><span>Release year:</span><?php echo $thisEntity->getDate(); ?></li>
                                    <li><span>Running time:</span><?php echo $thisEntity->getDuration(); ?></li>
                                </ul>

                                <div class="card__description card__description--details"><?php echo $thisEntity->overview(); ?>
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
                <iframe width="540" height="300" src="https://streamtape.com/e/<?php echo $thisEntity->videoID().'/'; ?>?rel=0&modestbranding=1&autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <!-- end player -->

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
                            <li class="facebook">
                                <div class="st-custom-button" data-network="facebook"><a href="#"><i class="icon ion-logo-facebook"></i></a></div>
                            </li>
                            <li class="twitter">
                                <div class="st-custom-button" data-network="twitter"><a href="#"><i class="icon ion-logo-twitter"></i></a></div>
                            </li>
                            <li class="vk">
                                <div class="st-custom-button" data-network="vk"><a href="#"><i class="icon ion-logo-vk"></i></a></div>
                            </li>
                             <li class="instagram">
                                 <div class="st-custom-button" data-network="sharethis"><a href="#"><i class="icon ion-ios-more"></i></a></div>
                            </li>
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
