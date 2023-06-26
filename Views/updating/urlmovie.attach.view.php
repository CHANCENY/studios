<?php

use GlobalsFunctions\Globals;
use Modules\Movies\Movie;

if(!empty(Globals::get('movieId')) && Globals::get('movieUrl')){
    echo \ApiHandler\ApiHandlerClass::stringfiyData(['status'=>
        (new Movie())->updateMovie(['url'=>Globals::get('movieUrl')],Globals::get('movieId'))
    ]);
    exit;
}

$movies = (new Movie())->movies();

$position = Globals::get('page');

$render = new \Modules\Renders\RenderHandler($movies);
$movies = $render->getOutPutRender();
$pagination = $render->getPositions();

$previous = 0;
$next = 0;
if(!empty($position)){
    $previous = intval($position) - 1;
    $next = intval($position) + 1;
}
?>
<section class="container w-100 mt-lg-5">
    <div class="d-inline-flex float-end" id="msg"></div>
 <div class="m-auto">
     <table class="table">
         <thead class="text-white-50">
         <tr>
             <th>Movie title</th>
             <th>Movie release date</th>
             <th>Movie Link</th>
             <th>Actions</th>
         </tr>
         </thead>
         <tbody class="text-white-50" id="total-movie" data="<?php echo count($movies ?? []); ?>">
         <?php if(!empty($movies)): ?>
         <?php $i = 0; foreach ($movies as $key=>$movie): ?>
         <tr>
             <td><?php echo $movie['title'] ?? null; ?></td>
             <td><?php echo $movie['release_date'] ?? null; ?></td>
             <td>
                 <input type="hidden" class="mx-lg-3" name="movieId" value="<?php echo $movie['movie_id'] ?? null; ?>" id="movie-id-<?php echo $i ?? null; ?>">
                 <input type="url" name="movieurl" value="<?php echo $movie['url'] ?? null; ?>" id="movie-url-<?php echo $i ?? null; ?>">
             </td>
             <td>
                 <a href="#" id="save-<?php echo $i; ?>">Save Changes</a>
                 <a href="edit-all?movie=<?php echo $movie['movie_id'] ?? null; ?>" class="ms-5">Edit All</a>
             </td>
         </tr>
         <?php $i++; endforeach; ?>
         <?php endif; ?>
         </tbody>
     </table><?php if(!empty($pagination) && count($pagination) > 1): ?>
     <nav aria-label="..." class="mt-lg-5 w-100 mb-lg-5">
             <ul class="pagination m-auto">
                 <?php if(!empty($position)): ?>
                     <li class="page-item">
                         <a class="page-link" href="<?php echo Globals::url(); ?>?page=<?php echo strval($previous); ?>">Previous</a>
                     </li>
                 <?php endif; ?>
                 <?php foreach ($pagination as $key=>$page): ?>
                     <li class="page-item <?php echo $page == $position ? 'active' : null; ?>">
                         <a class="page-link" href="<?php echo Globals::url(); ?>?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                     </li>
                 <?php endforeach; ?>
                 <?php if(!empty($position)): ?>
                     <li class="page-item">
                         <a class="page-link" href="<?php echo Globals::url(); ?>?page=<?php echo $next; ?>">Next</a>
                     </li>
                 <?php endif; ?>
             </ul>
         </nav><?php endif; ?>
 </div>
    <script src="assets/my-styles/js/movie-update.js"></script>
</section>
