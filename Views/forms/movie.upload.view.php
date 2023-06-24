<?php

use GlobalsFunctions\Globals;
use Modules\Movies\Movie;

if(Globals::method() === 'POST'){
    if(!empty(Globals::post('moviebtn'))){
        if(!empty(Globals::post('title'))
           && !empty(Globals::post('type'))
            && !empty(Globals::post('duration'))
            && !empty(Globals::post('release_date'))
            && !empty(Globals::post('url'))
            && !empty(Globals::files('image'))
        ){
            $result = (new Movie())->addMovie();
            $list = "<ul>";
            foreach ($result as $key=>$value){
                $list .= "<li class='list-group-item'>{$key} : $value </li>";
            }
            echo Alerts\Alerts::alert('info',$list.'</ul>');
        }else{
            echo \Alerts\Alerts::alert('danger', "Failed to upload movie");
        }
    }
}

?>
<form class="text-white container mt-5" method="POST" action="<?php echo Globals::uri(); ?>" enctype="multipart/form-data">
    <div class="m-auto w-50">
        <div class="form-group mt-3">
            <label for="movie-name">Movie Title</label>
            <input type="text" name="title" id="movie-name" class="form-control" placeholder="Title">
        </div>

        <div class="form-groupmt-3">
            <label for="movie-image">Movie Image</label>
            <input type="file" name="image" id="movie-image" class="form-control" placeholder="Movie Image">
        </div>

        <div class="form-groupmt-3">
            <label for="movie-genre">Movie Genre</label>
            <input type="text" name="type" id="movie-genre" class="form-control" placeholder="Movie Genre">
        </div>

        <div class="form-group mt-3">
            <label for="movie-duration">Movie Duration</label>
            <input type="text" name="duration" id="movie-duration" class="form-control" placeholder="Movie Duration">
        </div>

        <div class="form-group mt-3">
            <label for="movie-url">Movie Url</label>
            <input type="url" name="url" id="movie-url" class="form-control" placeholder="Movie Url">
        </div>

        <div class="form-group mt-3">
            <label for="movie-genre">Movie Release</label>
            <input type="date" name="release_date" id="movie-release" class="form-control" placeholder="Movie Release">
        </div>

        <div class="form-group mt-3">
            <label for="movie-genre">Movie Description</label>
            <textarea type="date" name="description" id="movie-description" class="form-control" placeholder="Movie Description"></textarea>
        </div>

        <div class="form-group mt-5 mt-3">
            <button type="submit" name="moviebtn" class="btn btn-outline-light" value="movie-upload">Upload Movie</button>
        </div>
    </div>
    </div>
</form>
