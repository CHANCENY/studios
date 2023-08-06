<?php

use GlobalsFunctions\Globals;
use Modules\Imports\Additionals;
use Modules\Movies\Movie;
use Modules\Shows\ShowsHandlers;

$result = [];
$title = "";
$idPassed = 0;

$type = "movies";

if(!empty(Globals::get('show'))){
    $show = (new ShowsHandlers())->showById(Globals::get('show'));
    $title = $show['title'] ?? null;
    $idPassed = Globals::get('show');
    $type = "shows";
}

if(!empty(Globals::get("movie"))){
    $movie = (new Movie())->getMovie(Globals::get('movie'));
    $title = $movie[0]['title'] ?? null;
    $idPassed = Globals::get('movie');
}

if(!empty($title)){
    $result = (new Additionals($title))->search($type)->getDataFound();
}

if(!empty(Globals::get("internal_id"))){
   $params = Globals::params();

   $data = [
       "internal_id"=>Globals::get('internal_id'),
       "bundle"=>Globals::get("type_bundle"),
       "tm_id"=>Globals::get('id'),
       "popularity"=> Globals::get('popularity'),
       "vote_average"=> Globals::get('vote_average'),
       "vote_count"=> Globals::get('vote_count'),
       "original_language"=> Globals::get('original_language'),
       "origin_country"=>Globals::get("origin_country")
   ];
   if((new Additionals())->saveAdditional($data)){
       echo \Alerts\Alerts::alert("info", "<p>Successfully added some additional information to you content</p>");
   }else{
       echo \Alerts\Alerts::alert("warning", "Failed to add additional info ".Globals::get('original_name') ?? Globals::get("name"));
   }
}

if(!empty(Globals::get("internalmovie_id"))){
    $data = [
        "internal_id"=>Globals::get("internalmovie_id"),
        "bundle"=>Globals::get("type_bundle"),
        "tm_id"=>Globals::get('id'),
        "popularity"=> Globals::get('popularity'),
        "vote_average"=> Globals::get('vote_average'),
        "vote_count"=> Globals::get('vote_count'),
        "original_language"=> Globals::get('original_language'),
    ];
    if((new Additionals())->saveAdditional($data)){
        echo \Alerts\Alerts::alert("info", "<p>Successfully added additional information</p>");
    }else{
        echo \Alerts\Alerts::alert("warning", "<p>Failed to save additional information</p>");
    }
}


function buildParams($array): string
{
    $line = "";
    foreach ($array as $key=>$value){
        $v = "";
        if(gettype($value) === 'array'){
            $v = implode(',', $value);
        }else{
            $v = $value;
        }
        $line .= "$key=$v&";
    }
    return substr($line,0, strlen($line) - 1);
}

?>
<?php if($type === "shows"): ?>
    <section class="container mt-lg-5">
    <div class="m-auto">
        <table class="table text-white-50">
            <thead>
              <tr>
                  <th>SHOW TITLE</th>
                  <th>DATE</th>
                  <th>POSTER IMAGE</th>
                  <th>ACTION</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($result)): ?>
              <?php foreach ($result as $key=>$value): ?>
                  <tr>
                      <td><?php echo $value['name'] ?? $value['original_name'] ?? null; ?></td>
                      <td><?php echo $value['first_air_date'] ?? null; ?></td>
                      <td>
                          <img style="width: 10rem;" src="https://image.tmdb.org/t/p/w500<?php echo $value['poster_path'] ?? $value['backdrop_path'] ?? null; ?>" />
                      </td>
                      <td>
                          <a href="<?php $value['internal_id'] = $idPassed; $value['type_bundle'] = $type; echo Globals::url().'?'.buildParams($value); ?>">Save This Show Additional Info</a>
                      </td>
                  </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php else: ?>
    <section class="container mt-lg-5">
        <div class="m-auto">
            <table class="table text-white-50">
                <thead>
                <tr>
                    <th>MOVIE TITLE</th>
                    <th>DATE</th>
                    <th>POSTER IMAGE</th>
                    <th>ACTION</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($result)): ?>
                    <?php foreach ($result as $key=>$value): ?>
                        <tr>
                            <td><?php echo $value['original_title'] ?? $value['original_name'] ?? null; ?></td>
                            <td><?php echo $value['release_date'] ?? null; ?></td>
                            <td>
                                <img style="width: 10rem;" src="https://image.tmdb.org/t/p/w500<?php echo $value['poster_path'] ?? $value['backdrop_path'] ?? null; ?>" />
                            </td>
                            <td>
                                <a href="<?php $value['internalmovie_id'] = $idPassed; $value['type_bundle'] = $type; echo Globals::url().'?'.buildParams($value); ?>">Save This Show Additional Info</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
