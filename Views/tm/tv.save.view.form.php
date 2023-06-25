<?php use GlobalsFunctions\Globals;

@session_start();

$showDetails = \Modules\Shows\ShowsHandlers::getShowTmDB(Globals::get('id'));

$data = [];
$genreType = "";
$seasons = [];
$episodes = [];

if(empty($showDetails['error'])){
    $m = $showDetails['data'];
   $data['title'] = $m['name'] ?? $m['original_name'];
   $data['description'] = $m['overview'] ?? null;
   $data['release_date'] = $m['first_air_date'] ?? null;
   $data['show_image'] = generateImage($m['poster_path'] ?? $m['backdrop_path']);

   $genreType = implode(',', map($result['genres'] ?? [], 'name'));
   $seasons = makeSeasons($m['seasons']);
   $episodes = episodeGather(Globals::get('id'),$m['number_of_seasons']);

   $result = \Modules\Shows\ShowsHandlers::saveTmDBShow($data, $seasons, $episodes);

   $out = "<ul class='list-group'>";
   foreach ($result as $key=>$value){
       $out .= "<li class='list-group-item mb-2'>$value</li>";
   }
   echo \Alerts\Alerts::alert('info', $out."</ul>");
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


function makeSeasons($seasons){

    $list = [];
    foreach ($seasons as $key=>$value){
        $list[] = [
            'season_name'=>$value['name'],
            'season_image'=>generateImage($value['poster_path']),
            'episode_count'=>$value['episode_count'],
            'description' => $value['overview'],
            'air_date'=> $value['air_date'],
            'season_number'=>$value['season_number']
        ];
    }
    return $list;
}

function generateImage($link):string
{
    if(empty($link)){
        return "";
    }
    $file = (new SplFileInfo($link))->getExtension();
    $file = "file.".$file;
    $gene = \FileHandler\FileHandler::saveFile($file,"https://image.tmdb.org/t/p/w500". $link);
    $list = explode('/', $gene);
    return "Files/". end($list);
}


function episodeGather($show, $count){

    $list = [];
    for($i = 1; $i <= $count; $i++){
        $episodes = \Modules\Shows\ShowsHandlers::getAllEpisodesTmDB($show, $i)['data']['episodes'] ?? [];
        if(!empty($episodes)){
           foreach ($episodes as $key=>$value){
               $list[$value['season_number']][] = [
                   'title'=>$value['name'],
                   'duration'=>$value['runtime'],
                   'epso_description'=>$value['overview'],
                   'epso_image'=>generateImage($value['still_path']),
                   'epso_number'=>$value['episode_number'],
                   'air_date'=>$value['air_date'],
                   'publish'=>'no'
               ];
           }
        }
    }
    return $list;
}



?>