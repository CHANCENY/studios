<?php

use Datainterface\Selection;
use GlobalsFunctions\Globals;
use Modules\Shows\ShowsHandlers;

if(Globals::method() === 'POST'){
    Globals::redirect('index');
}

$show_id = Globals::get('show');

$show_id = Selection::selectById('tv_shows',['show_uuid'=>$show_id])[0]['show_id'] ?? 0;

$showFound = (new ShowsHandlers())->showById($show_id);
$fullShowForDropDown = (new ShowsHandlers())->listingShow($show_id);

$totalEpisode = 0;

global $showImage;

foreach ($fullShowForDropDown as $key=>$value){
    $totalEpisode += count($value);
}

$showName = $showFound['title'] ?? null;
$showImage = $showFound['show_image'] ?? null;
$showDescription = $showFound['description'] ?? null;


?>
<section class="container w-100 mt-5" id="title-show" data="<?php echo $showName; ?>">
    <div class="container text-white">
        <div class="row mt-3 mb-3 my-row">
            <div class="col-2 my-image-in-card">
                <img src="<?php echo $showImage; ?>" class="img-thumbnail zoom" alt="<?php echo $showName; ?>" style="width: 18rem;">
            </div>
            <div class="col">
                <h2 class="display-7"><?php echo $showName; ?></h2>
                <p class="lead"><?php echo $showDescription; ?></p>
                <div class="mt-4">
                    <i class="fa-solid fa-video mb-4"> <?php echo $totalEpisode > 0 ? $totalEpisode.' episodes' : null; ?></i>
                    <i class="fa-solid fa-photo-film-music ms-lg-3 mb-4"><?php  ?></i>
                </div>
            </div>
        </div>
        <div class="row mt-lg-5"><?php if(!empty($fullShowForDropDown)): ?><?php foreach ($fullShowForDropDown as $key=>$value): ?>
                <?php echo $key; ?><hr><?php \Core\Router::attachView('listing-card', $value); ?>
            <?php endforeach; ?><?php endif; ?></div>
    </div>
</section>
<div>
    <script src="assets/my-styles/js/listing.js"></script>
</div>
