<?php use Modules\Movies\Movie;
use Modules\Search\Search;

@session_start();

$results = (new Search())->generalSearch(\GlobalsFunctions\Globals::get('q'));

function image($movie_id){
    return \Datainterface\Selection::selectById('images',['target_id'=>$movie_id])[0]['url_image'] ?? "";
}
?>
<?php if(!empty($results)): ?>
<div class="row w-100 m-auto"><?php foreach ($results as $key=>$value): ?>
<div class="card bg-dark m-auto" style="width: 12rem;">
  <img src="<?php echo $value['show_image'] ?? image($value['movie_id']); ?>" class="card-img-top" alt="<?php echo $value['title'] ?? null; ?>">
  <div class="card-body">
    <h5 class="card-title"><?php echo $value['title'] ?? null; ?></h5>
    <p class="card-text text-white-50"><?php $date = (new DateTime($value['release_date']))->format('m-d-Y'); echo $date ?? null; ?></p>
    <a href="<?php echo isset($value['show_id']) ? 'view-tv-show?show='.$value['show_uuid'] : 'movie-stream?movie='.$value['movie_uuid'] ?? null; ?>" class="btn btn-primary">See More...</a>
  </div>
</div>
<?php endforeach; ?></div>


<?php endif; ?>
