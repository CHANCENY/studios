<?php @session_start();


/**
 *  loading files images
 */

$sources = [
    'movies'=>'sites/files/images/movies',
    'shows'=> 'sites/files/images/shows'
];

$destination = [
    'movies'=>'sites/files/watermark/movies',
    'shows'=> 'sites/files/watermark/shows'
];

function loadImages($s, $d): array
{
    $moviesImages = array_diff(scandir($s['movies']),['..','.']);
    $showsImages = array_diff(scandir($s['shows']),['..','.']);

    $desMoviesImages = array_diff(scandir($d['movies']),['..','.']);
    $desShowsImages = array_diff(scandir($d['shows']),['..','.']);

    $movies = array_diff($moviesImages, $desMoviesImages);
    $shows = array_diff($showsImages, $desShowsImages);
    return [
        'movies'=>$movies,
        'shows'=>$shows
    ];
}

function makeWatermarkImage($images, $source, $destination): array
{

    $processed = [];
    if(isset($images['movies'])){
      $chunked = array_chunk($images['movies'], 10);
      $movies = $chunked[0] ?? [];
      $i = 0;
      foreach ($movies as $key=>$movie){
          $from = $source['movies']."/".$movie;
          $to = $destination['movies']."/".$movie;
          $sample = "sites/files/watermark/samples/movie.png";
          if((new Modules\Imports\WaterMark($from,$to,$sample))->isResult()){
              $i++;
          }
      }

      $processed[] = "$i processed of movies";
    }

    if(isset($images['shows'])){
        $chunked = array_chunk($images['shows'], 10);
        $shows = $chunked[0] ?? [];
        $i = 0;
        foreach ($shows as $key=>$show){
            $from = $source['shows']."/".$show;
            $to = $destination['shows']."/".$show;
            $sample = "sites/files/watermark/samples/shows.png";
            if((new Modules\Imports\WaterMark($from,$to,$sample))->isResult()){
                $i++;
            }
        }

        $processed[] = "$i processed of shows";
    }
    return $processed;
}

$images = loadImages($sources, $destination);

$result = makeWatermarkImage($images,$sources, $destination);

foreach ($result as $k=>$value){
    echo $value.PHP_EOL;
}
exit;
?>