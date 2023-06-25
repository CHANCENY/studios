<?php

use GlobalsFunctions\Globals;
use Modules\Shows\ShowsHandlers;

if(Globals::method() === 'POST'){
    Globals::redirect('index');
}

$show_id = Globals::get('show');

$showFound = (new ShowsHandlers())->showById($show_id);
$fullShowForDropDown = (new ShowsHandlers())->listingShow($show_id);

$totalEpisode = 0;

foreach ($fullShowForDropDown as $key=>$value){
    $totalEpisode += count($value);
}

$showName = $showFound['title'] ?? null;
$showCover = $showFound['show_image'] ?? null;
$showDescription = $showFound['description'] ?? null;

?>
<section class="container w-100 mt-5" id="title-show" data="<?php echo $showName; ?>">
    <div class="container text-white">
        <div class="row mt-3 mb-3">
            <div class="col">
                <img src="<?php echo $showCover; ?>" class="img-thumbnail zoom" alt="<?php echo $showName; ?>">
            </div>
            <div class="col">
                <h2 class="display-7"><?php echo $showName; ?></h2>
                <p class="lead"><?php echo $showDescription; ?></p>
                <div class="mt-4">
                    <i class="fa-solid fa-video d-block mb-4"> <?php echo $totalEpisode > 0 ? $totalEpisode.' episodes' : null; ?></i>
                    <i class="fa-solid fa-photo-film-music d-block mb-4"> Mp4</i>
                </div>
            </div>
        </div>
        <div class="row mt-lg-5">
            <div class="col"><?php foreach ($fullShowForDropDown as $key=>$value): ?>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo str_replace(' ','-',$key); ?>" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $key; ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo str_replace(' ','-',$key); ?>">
                       <?php foreach ($value as $k=>$v): ?>
                           <?php if($v['publish'] === 'yes'): ?>
                               <li>
                                   <a class="dropdown-item" href="watch?w=<?php echo $v['episode_id'] ?? null; ?>"><?php echo $v['title'] ?? null;  ?></a>
                               </li>
                           <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div><?php endforeach; ?>
            </div>
            <div class="col">
               <textarea cols="4" rows="4" id="comment" class="form-control">Type comment or request here</textarea>
            </div>
            <div class="col">
                <button id="comment-send" type="button" class="btn btn-outline-light mb-4">Send Comment</button>
            </div>
        </div>
    </div>
</section>
<div>
    <script src="assets/my-styles/js/listing.js"></script>
</div>
