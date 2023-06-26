<?php

use Datainterface\Selection;
use GlobalsFunctions\Globals;
use Modules\Movies\Movie;

$movie = (new Movie())->getMovie(Globals::get('movie'))[0] ?? [];
if(!empty($movie)){
    unset($movie['movie_id']);
    unset($movie['created']);
    unset($movie['genre_id']);
    unset($movie['genre_name']);
    unset($movie['target_id']);
    unset($movie['url_image']);
    unset($movie['image_id']);
    unset($movie['related_movies']);
    $movie['type'] = Selection::selectById('genres',['genre_id'=>$movie['type']])[0]['genre_name'] ?? "";
}


if(Globals::method() === 'POST' && !empty(Globals::post('submit-movie-update'))){
    $movie = (new Movie())->getMovie(Globals::get('movie'))[0] ?? [];
    $id = $movie['genre_id'];
    unset($movie['movie_id']);
    unset($movie['created']);
    unset($movie['genre_id']);
    unset($movie['genre_name']);
    unset($movie['target_id']);
    unset($movie['url_image']);
    unset($movie['image_id']);
    unset($movie['related_movies']);
    unset($movie['type']);

    foreach ($movie as $key=>$value){
        $data[$key] = Globals::post($key) ?? $value;
    }
    $result = (new Movie())->updateMovie($data, Globals::get('movie'));
    if($result){
        echo \Alerts\Alerts::alert('info', "Updated movie ".Globals::post('title'));
    }else{
        echo \Alerts\Alerts::alert('warning', "Failed tp update movie".Globals::post('title'));
    }
}
$movie = (new Movie())->getMovie(Globals::get('movie'))[0] ?? [];
?>
<section class="container mt-lg-5">
    <div class="m-auto w-50 text-white-50">
        <form method="POST" class="form" action="<?php echo Globals::uri(); ?>">
          <?php if(!empty($movie)): ?>
          <?php foreach ($movie as $key=>$value): ?>
             <div class="form-group mb-4">
                 <label><?php echo $key ?? null; ?></label>
                 <input type="text" class="form-control" name="<?php echo $key ?? null; ?>" value="<?php echo $value ?? null; ?>">
             </div>
           <?php endforeach; ?>
          <div class="form-group">
              <button type="submit" value="v" class="btn btn-outline-light" name="submit-movie-update">Update Movie</button>
          </div>
          <?php endif; ?>
        </form>
    </div>
</section>
