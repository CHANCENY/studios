<?php

use GlobalsFunctions\Globals;

$privacy = file_get_contents(Globals::root()."/Views/policy/privacy.html");
$privacy = str_replace("Streamtape", "Streamstudio", $privacy);
$privacy = str_replace("streamtape", "Streamstudio", $privacy);
?>
<!-- page title -->
<section class="section section--first section--bg" data-bg="assets/main/img/section/section.jpg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section__wrap">
                    <!-- section title -->
                    <h2 class="section__title">Privacy</h2>
                    <!-- end section title -->

                    <!-- breadcrumb -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb__item"><a href="home">Home</a></li>
                        <li class="breadcrumb__item breadcrumb__item--active">Privacy</li>
                    </ul>
                    <!-- end breadcrumb -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end page title -->
<section class="section">
    <div class="container" style="color: whitesmoke;"><?php echo $privacy; ?>
    </div>
</section>
