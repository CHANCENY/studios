<?php

use Modules\Movies\Movie;
use Datainterface\Selection;
use GlobalsFunctions\Globals;

$m = Selection::selectById('movies', ['movie_uuid'=>Globals::get('movie')])[0]['movie_id'] ?? 0;

$movieDetails = (new Movie())->getMovie($m);
$m = $movieDetails[0] ?? [];

?>
<section class="container w-100 mt-5" id="title-show" data="<?php echo $m['title'] ?? null; ?>">
    <div class="container text-white">
        <div class="row mt-3 mb-3 my-row">
            <div class="col">
                <img src="<?php echo $m['url_image'] ?? null; ?>" class="img-thumbnail zoom my-image-in-card" style="width: 20rem" alt="show">
            </div>
            <div class="col">
                <h2 class="display-7"><?php echo $m['title'] ?? null; ?></h2>
                <p class="lead"><?php echo $m['description'] ?? null; ?></p>
                <div class="mt-4">
                    <i class="fa-solid fa-video d-block mb-4">&nbsp;<?php echo $m['genre_name'] ?? null; ?></i>
                    <i class="fa-solid fa-photo-film-music d-block mb-4"> <?php echo (new SplFileInfo($m['url']))->getExtension() ?? null; ?></i>
                    <i class="fa-solid fa-timer d-block mb-4"><?php echo str_ends_with(strtolower($m['duration']),'m') ? $m['duration'] : $m['duration']. ' mins'; ?></i>
                </div>
            </div>
        </div>
        <div class="row mt-lg-5">
            <div class="col-1 m-auto">
                <a class="btn btn-outline-light w-100 p-2 my-play-button" href="watch?m=<?php echo $m['movie_uuid'] ?? null; ?>" role="button"> <i class="fa-solid fa-play"></i></a>
            </div>
        </div>
    </div>
</section>
<div>
    <script src="assets/my-styles/js/listing.js"></script>
</div>