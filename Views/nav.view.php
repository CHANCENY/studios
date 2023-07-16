<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="url" content="<?php echo \GlobalsFunctions\Globals::uri(); ?>">
    <link rel="shortcut icon" href="https://streamstudios.online/Files/logo.png" type="image/x-icon">
    <title id="titlepage"><?php echo \GlobalsFunctions\Globals::viewTitleOnRequest(); ?></title>
    <?php $path = \GlobalsFunctions\Globals::urlComponents($_SERVER['REQUEST_URI'])['path'];
    $url = explode('/', $path);
    $url = end($url);
    echo \Core\RouteConfiguration::appendMetatags($url);
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/my-styles/css/styles.css">
    <link rel="stylesheet" href="assets/my-styles/css/fonts/Open_Sans/static/OpenSans-Light.ttf">
</head>
<body class="bg-dark text-white" style="overflow-x: hidden; font-family: Kanit;">
<nav class="navbar navbar-expand-lg navbar-light bg-dark text-white border-bottom border-dark rounded shadow">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <a class="navbar-brand mt-2 mt-lg-0" id="menu-0" href="index">
                <img src="https://streamstudios.online/Files/logo.png" height="20" alt="MDB Logo" loading="lazy"/>
            </a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 display-7">
                <li class="nav-item">
                    <a class="nav-link text-white-50" id="menu-1" href="tv-shows">Tv shows</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" id="menu-2" href="movies">Movies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" id="menu-4" href="individual-episodes">Individual Episodes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" id="menu-3" href="request-show-movie">Request</a>
                </li>
            </ul>
            <div class="float-end">
<!--                <input type="search" placeholder="Type search" id="myInput" class="dropdown-content form-control">-->
                <form autocomplete="off" action="">
                    <div class="autocomplete bg-dark" style="width:300px;">
                        <input class="form-control" id="myInput" type="text" name="myCountry" placeholder="Search">
                    </div>
                </form>
            </div>
        </div>
    </div>
</nav>
<?php
 \Core\Router::attachView('moderate-view');
 \Core\Router::attachView('block');
?>
