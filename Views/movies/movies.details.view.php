<?php

use GlobalsFunctions\Globals;
use Modules\Movies\Movie;

$movieDetails = (new Movie())->getMovie(Globals::get('movie') ?? 0);
$m = $movieDetails[0] ?? [];

?>
<section class="container w-100 mt-5" id="title-show" data="my show">
    <div class="container text-white">
        <div class="row mt-3 mb-3">
            <div class="col">
                <img src="<?php echo $m['url_image'] ?? null; ?>" class="img-thumbnail zoom" alt="show">
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
            <div class="col">
                <a class="btn btn-outline-light w-100 p-2" href="watch?m=<?php echo $m['movie_id'] ?? null; ?>" role="button"> <i class="fa-solid fa-play"></i></a>
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