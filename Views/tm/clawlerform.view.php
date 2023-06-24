<?php use GlobalsFunctions\Globals;
use Modules\Movies\Movie;

@session_start();


$formInputField = "";
$imageLink = "";
$result = \Modules\Imports\ImportHandler::requestMovie(Globals::get('id'));

$data = [];

if(!empty($result)){
    $data['title'] = Globals::get('original_title') ?? Globals::get('title');
    $data['release_date'] = Globals::get('release_date') ?? null;
    $data['duration'] = $result['runtime'] ?? null;
    $data['type'] = implode(',', map($result['genres'] ?? [], 'name'));
    $data['description'] = $result['overview'] ?? null;
    $imageLink = "https://image.tmdb.org/t/p/w500".$result['poster_path'] ?? null;
}else{
    $imageLink = "https://image.tmdb.org/t/p/w500".Globals::get('poster_path') ?? Globals::get('backdrop_path');
}

function map($array , $key){

    $list = [];
    foreach ($array as $k=>$value){
        if(gettype($value) == 'array'){
            $list[] = $value[$key];
        }
    }
    return$list;
}


foreach ($data as $key=>$value){
    $formInputField .= "<input type='hidden' name='$key' value='$value'>";
}

if(Globals::method() === 'POST'){
        if(!empty(Globals::post('tmdb'))){
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

<section class="container mt-lg-5">
    <div class="w-50 m-auto">
        <div class="mb-4 mt-5">
            <a href="<?php echo $imageLink; ?>" target="_blank">Download this movie cover image here</a>
        </div>
        <form class="form" method="POST" action="<?php echo Globals::uri(); ?>" enctype="multipart/form-data">
            <?php echo $formInputField; ?>
            <div class="form-group mt-5">
                <label for="image">Cover Image</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="form-group mt-4">
                <label for="url">URL MOVIE</label>
                <input type="url" name="url" class="form-control">
            </div>
            <button type="submit" class="btn btn-outline-light mt-lg-5" name="tmdb"  value="kk">ADD MOVIE</button>
        </form>
    </div>
</section>
