<?php

use Datainterface\Selection;
use GlobalsFunctions\Globals;
use Modules\Shows\ShowsHandlers;

$showEpisode = [];
$movie = [];
$videoId = "";
$title = "";
$link = "";
$link2 = "";
if(!empty(Globals::get('w'))){
    /**
     * bring show season episode
     */
    $ep = Selection::selectById('episodes',['episode_uuid'=>Globals::get('w')])[0]['episode_id'] ?? 0;
    $showEpisode = (new ShowsHandlers())->getEpisode($ep)[0] ?? [];
    $list = explode('/',$showEpisode['url'] ?? "");
    $videoId = $list[4] ?? null;
    $title = $showEpisode['title'];
    $link ="https://streamtape.com/e/$videoId";
    $link2 = $showEpisode['url'];
}else{
    /**
     * bring movie
     */
    $m = Selection::selectById('movies',['movie_uuid'=>Globals::get('m')])[0]['movie_id'] ?? 0;
    $movie = (new \Modules\Movies\Movie())->getMovie($m)[0] ?? [];
    $list = explode('/', $movie['url'] ?? "");
    $videoId = $list[4] ?? null;
    $title = $movie['title'];
    $link ="https://streamtape.com/e/$videoId";
    $link2 = $movie['url'];
}
?>
<div id="title" data="<?php echo $title ?? null; ?>"></div>
<h2 class="text-white-50 text-center text-capitalize"><?php echo $title ?? null; ?></h2>
<section class="video-container">
    <iframe src="<?php echo $link ?? null; ?>" width="800" height="600" allowfullscreen allowtransparency allow="autoplay"></iframe>
</section>
<div class='mt-lg-5 text-center text-white-50'>
    <?php if(!empty($link2)): ?>
        <a href='<?php echo $link2 ?? null; ?>' target='_blank' class='mt-lg-5 text-decoration-none'>Play here if top player failed</a>
    <?php endif; ?>
</div>
<section class="container mt-lg-5">
    <div class="m-auto text-center">
        <h1>If Link of Show/Episode or Movie not Found</h1><p>You can request this episode or movie by visit Request page.
            Search show or movie name then if it tv show please state season and episode you are looking for in text box
            provide on page.<a href='request-show-movie'>Visit Request page.</a> Upon request you will be notified within 4hrs
            with link of show or movie you requested. Thank you</p>
    </div>
</section>




<script type="application/javascript">
    const div = document.getElementById('title');
    document.getElementById('titlepage').textContent = document.getElementById('titlepage').textContent + div.getAttribute('data');
</script>