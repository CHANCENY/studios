<?php

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
    $showEpisode = (new ShowsHandlers())->getEpisode(Globals::get('w'))[0] ?? [];
    $list = explode('/',$showEpisode['url']);
    $videoId = $list[4];
    $title = $showEpisode['title'];
    $link ="https://streamtape.com/e/$videoId";
    $link2 = $showEpisode['url'];
}else{
    /**
     * bring movie
     */
    $movie = (new \Modules\Movies\Movie())->getMovie(Globals::get('m'))[0] ?? [];
    $list = explode('/', $movie['url']);
    $videoId = $list[4];
    $title = $movie['title'];
    $link ="https://streamtape.com/e/$videoId";
    $link2 = $movie['url'];
}
?>
<div id="title" data="<?php echo $title ?? null; ?>"></div>
<section class="container w-100 m-auto text-center">
    <iframe src="<?php echo $link ?? null; ?>/" width="1000" height="800" style="margin: auto" allowfullscreen allowtransparency allow="autoplay"  scrolling="no" frameborder="0"></iframe>
    <div class='mt-lg-5'>
        <a href='<?php echo $link2 ?? null; ?>' target='_blank' class='mt-lg-5 text-decoration-none'>Play here if top player failed</a>
    </div>
</section>
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