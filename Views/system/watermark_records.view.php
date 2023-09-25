<?php @session_start();

$sources = [
    'movies'=>'sites/files/images/movies',
    'shows'=> 'sites/files/images/shows'
];

$destination = [
    'movies'=>'sites/files/watermark/movies',
    'shows'=> 'sites/files/watermark/shows'
];

function unprocessed($s): array
{
    $moviesImages = array_diff(scandir($s['movies']),['..','.']);
    $showsImages = array_diff(scandir($s['shows']),['..','.']);
    return ['movies'=>count($moviesImages), 'shows'=>count($showsImages)];
}

function processed($d): array
{
    $desMoviesImages = array_diff(scandir($d['movies']),['..','.']);
    $desShowsImages = array_diff(scandir($d['shows']),['..','.']);
    return ['movies'=>count($desMoviesImages), 'shows'=>count($desShowsImages)];
}

$images = unprocessed($sources);
$images1 = processed($destination);
?>
<section class="container mt-lg-5">
    <div class="m-auto">
        <table class="table text-white-50">
            <thead>
             <tr>
                 <th>Type Images</th>
                 <th>Watermark Image Total</th>
                 <th>Processed Image Total</th>
             </tr>
            </thead>
            <tbody>
              <tr>
                  <td>Movies Images</td>
                  <td><?php echo $images['movies'] ?? 0; ?></td>
                  <td><?php echo $images1['movies'] ?? 0; ?></td>
              </tr>
              <tr>
                  <td>Shows images</td>
                  <td><?php echo $images['shows'] ?? 0; ?></td>
                  <td><?php echo $images1['shows'] ?? 0; ?></td>
              </tr>
            </tbody>
        </table>
    </div>
    <div>
        <h4>Locations:</h4>
        <div>
            <span><b>Watermarked Movies Images</b>&nbsp;&nbsp;<?php echo $destination['movies']; ?></span><br>
            <span><b>Watermarked Shows Images</b>&nbsp;&nbsp;<?php echo $destination['shows']; ?></span><br>
        </div>
        <div>
            <span><b>Movies Images</b>&nbsp;&nbsp;<?php echo $sources['movies']; ?></span><br>
            <span><b>Shows Images</b>&nbsp;&nbsp;<?php echo $sources['shows']; ?></span><br>
        </div>
    </div>
</section>
