<?php
@session_start();
use GlobalsFunctions\Globals;
use Modules\Renders\SEOTags;

@session_start();

global $token;

/**
 * Token to be use to set data for seo and token to send via XMLHTTP to get seo data
 */

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="url" content="<?php echo SEOTags::token(); ?>">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">

    <!-- Include jQuery library from Google CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/main/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="assets/main/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="assets/main/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/main/css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="assets/main/css/nouislider.min.css">
    <link rel="stylesheet" href="assets/main/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/main/css/plyr.css">
    <link rel="stylesheet" href="assets/main/css/photoswipe.css">
    <link rel="stylesheet" href="assets/main/css/default-skin.css">
    <link rel="stylesheet" href="assets/main/css/main.css">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="assets/main/icon/favicon-32x32.png" sizes="32x32">
    <link rel="apple-touch-icon" href="assets/main/icon/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/main/icon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/main/icon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/main/icon/apple-touch-icon-144x144.png">

    <title id="titlepage"><?php echo Globals::viewTitleOnRequest(); ?></title>

    <!--Meta tag dynamics-->
    <?php $path = Globals::urlComponents($_SERVER['REQUEST_URI'])['path'];
    $url = explode('/', $path);
    $url = end($url);
    echo \Core\RouteConfiguration::appendMetatags($url);
    ?>
    <!-- end meta -->

    <!--shareThis-->
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=64e6383e0ba20000199f7568&product=inline-share-buttons' async='async'></script>
    <!-- end shareThis -->

</head>
<body class="body">

<!-- header -->
<header class="header">
    <div class="header__wrap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="header__content">
                        <!-- header logo -->
                        <a href="home" class="header__logo">
                            <img src="assets/main/img/logo.svg" alt="">
                        </a>
                        <!-- end header logo -->

                        <!-- header nav -->
                        <ul class="header__nav">
                            <!-- dropdown -->
                            <li class="header__nav-item">
                                <a class="dropdown-toggle header__nav-link" href="home" role="button" id="dropdownMenuHome" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Home</a>

                                <ul class="dropdown-menu header__dropdown-menu" aria-labelledby="dropdownMenuHome">
                                    <li><a href="home">Home slide show</a></li>
                                    <li><a href="static">Home static</a></li>
                                </ul>
                            </li>
                            <!-- end dropdown -->

                            <!-- dropdown -->
                            <li class="header__nav-item">
                                <a class="dropdown-toggle header__nav-link" href="#" role="button" id="dropdownMenuCatalog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Catalog</a>

                                <ul class="dropdown-menu header__dropdown-menu" aria-labelledby="dropdownMenuCatalog">
                                    <li><a href="catalogolue-films-grid-lisitng">Movies Catalog Grid</a></li>
                                    <li><a href="movies-listing">Movies Catalog Listing</a></li>
                                    <li><a href="catalogolue-series-grid-list">Shows Catalog Grid</a></li>
                                    <li><a href="series-listing">Shows Catalog Listing</a></li>
                                </ul>
                            </li>
                            <!-- end dropdown -->

<!--                            <li class="header__nav-item">-->
<!--                                <a href="pricing.html" class="header__nav-link">Pricing Plan</a>-->
<!--                            </li>-->

                            <li class="header__nav-item">
                                <a href="/help" class="header__nav-link">Help</a>
                            </li>

                            <!-- dropdown -->
                            <li class="dropdown header__nav-item">
                                <a class="dropdown-toggle header__nav-link header__nav-link--more" href="#" role="button" id="dropdownMenuMore" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon ion-ios-more"></i></a>

                                <ul class="dropdown-menu header__dropdown-menu" aria-labelledby="dropdownMenuMore">
                                    <li><a href="/how-to-navigate-here-at-stream-studios">Navigation Help</a></li>
                                    <?php if(empty(Globals::user())): ?>
                                    <li><a href="stream-studios-join">Sign Up</a></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            <!-- end dropdown -->
                        </ul>
                        <!-- end header nav -->

                        <!-- header auth -->
                        <div class="header__auth">
                            <button class="header__search-btn" type="button">
                                <i class="icon ion-ios-search"></i>
                            </button><?php if(empty(Globals::user())): ?>
                            <a href="login-user-at-stream-studios<?php echo !empty(Globals::uri()) ? '?destination='.Globals::uri() : null; ?>" class="header__sign-in">
                                <i class="icon ion-ios-log-in"></i>
                                <span>sign in</span>
                            </a><?php else: ?>
                            <a href="logout-user-at-stream-studios" class="header__sign-in">
                                <i class="icon ion-ios-log-in"></i>
                                <span>sign out <?php echo (new  \User\User())->firstName(); ?></span>
                            </a><?php endif; ?>
                        </div>
                        <!-- end header auth -->

                        <!-- header menu btn -->
                        <button class="header__btn" type="button">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                        <!-- end header menu btn -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- header search -->
    <form action="filtering-stream" method="GET" class="header__search">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="header__search-content">
                        <input type="text" name="title" placeholder="Search for a movie, TV Series that you are looking for">

                        <button type="submit">search</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- end header search -->
</header>
<!-- end header -->
