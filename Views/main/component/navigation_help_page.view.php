<?php use GlobalsFunctions\Globals;
use Modules\Imports\NavigationHelp;


@session_start();

$content = (new NavigationHelp())->contents();

?>
<!-- page title -->
<section class="section section--first section--bg" data-bg="assets/main/img/section/section.jpg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section__wrap">
                    <!-- section title -->
                    <h2 class="section__title">How to navigate stream studios</h2>
                    <!-- end section title -->

                    <!-- breadcrumb -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb__item"><a href="home">Home</a></li>
                        <li class="breadcrumb__item breadcrumb__item--active">How to navigate stream studios</li>
                    </ul>
                    <!-- end breadcrumb -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end page title -->

<!-- faq -->
<section class="section"><?php if(!empty($content)): foreach ($content as $key=>$value): ?>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="faq">
                    <h3 class="faq__title"><?php echo $value['title'] ?? null; ?><?php echo $value['paragraphs'] ?? null; ?>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="container"><div class="row">
                        <div class="faq"><?php if(!empty($value['videos'])): $videos = explode(',', $value['videos'] ?? ""); ?><?php foreach ($videos as $k=>$v): ?>
                                <p class="faq__text"><?php $video = (new NavigationHelp($v))->findFile(); ?>
                                    <!-- player -->
                                <div class="col-12 col-xl-6">
                                    <video controls crossorigin playsinline poster="../../../cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg" id="player">
                                        <!-- Video files -->
                                        <source src="<?php echo Globals::protocal().'://'.$video->getURL(); ?>" type="video/<?php echo $video->getExtension(); ?>" size="576">
                                        <source src="<?php echo Globals::protocal().'://'.$video->getURL(); ?>" type="video/<?php echo $video->getExtension(); ?>" size="720">
                                        <source src="<?php echo Globals::protocal().'://'.$video->getURL(); ?>" type="video/<?php echo $video->getExtension(); ?>" size="1080">
                                        <source src="<?php echo Globals::protocal().'://'.$video->getURL(); ?>" type="video/<?php echo $video->getExtension(); ?>" size="1440">

                                        <!-- Caption files -->
                                        <track kind="captions" label="English" srclang="en" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt"
                                               default>
                                        <track kind="captions" label="Français" srclang="fr" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt">

                                        <!-- Fallback for browsers that don't support the <video> element -->
                                        <a href="<?php echo Globals::protocal().'://'.$video->getURL(); ?>" download>Download</a>
                                    </video>
                                </div>
                                <!-- end player -->
                                </p>
                            <?php endforeach; endif; ?>
                        </div>
                        <div class='faq'>
                            <?php if(!empty($value['images'])): $images = explode(',',$value['images'] ?? ""); ?>
                                <?php foreach ($images as $k=>$v): ?>
                                    <?php $url = (new NavigationHelp($v))->findFile()->getURL(); ?>
                                    <img width="200" style="margin: 1rem !important;" src="<?php echo Globals::protocal().'://'.$url;  ?>" alt="no-alt">
                                <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><?php endforeach; endif; ?>
<!-- end faq -->
