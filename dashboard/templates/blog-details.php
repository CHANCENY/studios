<?php

use Datainterface\Insertion;
use GlobalsFunctions\Globals;
use groups\GroupMovies;
use Modules\Imports\ImportHandler;

$movieID = Globals::get("movie-id");
if(empty($movieID))
{
    Globals::redirect("/movies/listing");
    exit;
}

$movieDetails = ImportHandler::requestMovie($movieID);

function body($movieDetails): string
{
    $title = $movieDetails['title'] ?? $movieDetails['original_title'] ?? null;
    $overview = $movieDetails['overview'] ?? null;
    $image = $movieDetails['poster_path'] ?? $movieDetails['backdrop_path'] ?? null;
    $vote = $movieDetails['vote_count'] ?? 0;
    $date =  (new DateTime($movieDetails['release_date']))->format("F d, Y");
    $byName = $movieDetails['production_companies'][0]['name'] ?? null;

    return <<<EOD
 <article class="blog blog-single-post">
                                <h3 class="blog-title">$title </h3>
                                <div class="blog-info clearfix">
                                    <div class="post-left">
                                        <ul>
                                            <li><a href="#."><i class="fa fa-calendar"></i> <span>$date</span></a></li>
                                            <li><a href="#."><i class="fa fa-user-o"></i> <span>$byName</span></a></li>
                                        </ul>
                                    </div>
                                    <div class="post-right"><a href="#."><i class="fa fa-comment-o"></i>$vote Count</a></div>
                                </div>
                                <div class="blog-image">
                                    <a href="#."><img alt="" src="http://image.tmdb.org/t/p/w500$image" class="img-fluid"></a>
                                </div>
                                <div class="blog-content">
                                 <p>$overview</p>
                                </div>
                            </article>
EOD;

}

function tags($movieDetails): string{
    $genres = $movieDetails['genres'] ?? [];
    $genresLine = null;
    foreach ($genres as $key=>$value) {
        $name = $value['name'];
       $genresLine .= <<<EOD
 <li><a href="#." class="tag">$name</a></li>
EOD;
    }
    return <<<EOD
<div class="widget tags-widget">
                            <h5>Tags</h5>
                            <ul class="tags">
                                $genresLine
                            </ul>
                        </div>
EOD;

}


function otherInfo($movieDetails): string
{
    $duration = $movieDetails['runtime'] ?? 0;
    $vote_average = $movieDetails['vote_average'] ?? 0.0;
    $pop = $movieDetails['popularity'] ?? 0;
    $oriLang = $movieDetails['spoken_language'][0]['english_name'] ?? null;
    return <<<EOD
<div class="widget category-widget">
                            <h5>Movie More Info</h5>
                            <ul class="categories">
                                <li><a href="#."><i class="fa fa-long-arrow-right"></i> Duration $duration minutes</a></li>
                                <li><a href="#."><i class="fa fa-long-arrow-right"></i> Vote Average $vote_average</a></li>
                                <li><a href="#."><i class="fa fa-long-arrow-right"></i> Popularity $pop</a></li>
                                <li><a href="#."><i class="fa fa-long-arrow-right"></i> Language $oriLang</a></li>
                            </ul>
                        </div>
EOD;

}

function productionCompanies($movieDetails): string
{
    $companies = $movieDetails['production_companies'] ?? [];
    $list = null;
    foreach ($companies as $key=>$value)
    {
        $logo = $value['logo_path'] ?? null;
        $name = $value['name'] ?? null;
        $list .= <<<EOD
 <li>
                                    <div class="post-thumb">
                                        <a href="#">
                                            <img class="img-fluid" src="http://image.tmdb.org/t/p/w500$logo" alt="">
                                        </a>
                                    </div>
                                    <div class="post-info">
                                        <h4>
											<a href="blog-details.html">$name</a>
										</h4>
                                    </div>
                                </li>
EOD;

    }
    return <<<EOD
<div class="widget post-widget">
                            <h5>Latest Posts</h5>
                            <ul class="latest-posts">
                                $list
                            </ul>
                        </div>
EOD;

}

function form($movieDetails): string{

    if(Globals::method() === "POST" && !empty(Globals::post("addmovietmdb")))
    {
        if(postTMDBHandler($movieDetails)){
            Globals::redirect("/movies/listing");
            exit;
        }
    }

    $genres = $movieDetails['genres'] ?? [];
    $genresLine = null;
    foreach ($genres as $key=>$value) {
        $name = $value['name'];
        $genresLine[] = $name;
    }
    $name = implode("|", $genresLine ?? []);
    $genreType = \Datainterface\Selection::selectById("genres", ['genre_name'=>$name]);
    if(!empty($genreType))
    {
        $genreType = $genreType[0]['genre_id'];
    }else{
        $genreType = Insertion::insertRow("genres", ['genre_name'=>$name]);
    }

    $post = $movieDetails['poster_path'] ?? $movieDetails['backdrop_path'] ?? null;
    if(!empty($post))
    {
        $post = (new GroupMovies())->uploadImageFromUrl("http://image.tmdb.org/t/p/w500$post",(new GroupMovies())->expectedRowID());
    }
    $required = [
        "title" => $movieDetails['title'] ?? $movieDetails['original_title'] ?? null,
        "description" => $movieDetails['overview'] ?? null,
        "type"=>$genreType,
        "movie_image"=>$post,
        "duration"=> $movieDetails['runtime'] ?? 0,
        "movie_id"=> (new GroupMovies())->expectedRowID(),
        "release_date" => $movieDetails['release_date'] ?? null,
        "movie_publish"=> "yes"
    ];

    $field = null;
    foreach ($required as $key=>$value)
    {
      $field .= "<input type='hidden' name='$key' value='$value'>".PHP_EOL;
    }
    $uri = Globals::uri();

    return <<<EOD
 <form method="POST" action="$uri" class="search-form">
                                <div class="input-group">
                                    <input type="url" placeholder="URL" name="url" class="form-control">
                                    $field
                                    <div class="input-group-append">
                                        <button name="addmovietmdb" value="addmtm" type="submit" class="btn btn-primary submit-btn">Create Movie</button>
                                    </div>
                                </div>
                            </form>
EOD;

}


function postTMDBHandler($movieDetails): bool
{
    $required = ['title', "description", "url", "type", "movie_image", "duration", "movie_id", "release_date", "movie_publish"];
    $data = [];

    $imageUUID = explode("=", Globals::post('movie_image'));
    $imageUUID = end($imageUUID);
    foreach ($required as $key=>$field)
    {
        if(!empty(Globals::post($field)))
        {
            $data[$field] = Globals::post($field);
        }
        elseif (!empty(Globals::files($field)))
        {
            $data[$field] = (new GroupMovies())->uploadNewImage(Globals::files($field),Globals::post("movie_id"));
        }else{
            if((new GroupMovies())->removeNewUploadedImage($imageUUID, (new GroupMovies())->expectedRowID()))
            {
                return false;
            }
        }
    }

    try {
        $date = (new DateTime(str_replace("/", "-", $data['release_date'])))->format("Y-m-d");
        $data['release_date'] = $date;
        unset($data['movie_id']);
        unset($data['addmovietmdb']);
    }catch (Throwable $e){

    }
    $data['movie_uuid'] = \Json\Json::uuid();

    if((new GroupMovies())->saveAdditionalInfo($movieDetails, (new GroupMovies())->expectedRowID()))
    {
        if(Insertion::insertRow("movies", $data))
        {
            $title = $data['title'];
            (new \groups\Notifications())->movieUploaded($title);
            return true;
        }else{
            if((new GroupMovies())->removeNewUploadedImage($imageUUID, (new GroupMovies())->expectedRowID()))
            {
                return false;
            }
        }
    }
    (new GroupMovies())->removeNewUploadedImage($imageUUID, (new GroupMovies())->expectedRowID());
    return false;
}

?>
<!DOCTYPE html>
<html lang="en">

<!-- blog-details23:51-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="https://dashboard.streamstudios.online/assets/img/favicon.ico">
    <title>Preclinic - Medical & Hospital - Bootstrap 4 Admin Template</title>
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/style.css">
    <script src="https://dashboard.streamstudios.online/assets/js/dashboard/cookies.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--[if lt IE 9]>
    <script src="https://dashboard.streamstudios.online/assets/js/html5shiv.min.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="main-wrapper">
        <div class="header">
            <div class="header-left">
                <a href="dashboard" class="logo">
                    <img src="https://dashboard.streamstudios.online/assets/img/logo.png" width="35" height="35" alt=""> <span>Preclinic</span>
                </a>
            </div>
            <a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown d-none d-sm-block">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"><i class="fa fa-bell-o"></i> <span class="badge badge-pill bg-danger float-right" id="notification-count">0</span></a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span>Notifications</span>
                        </div>
                        <div class="drop-scroll">
                            <ul class="notification-list" id="notification-list">
                                <!--alert listing here-->
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="/notifications">View all Notifications</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown d-none d-sm-block">
                    <a href="javascript:void(0);" id="open_msg_box" class="hasnotifications nav-link"><i class="fa fa-comment-o"></i> <span class="badge badge-pill bg-danger float-right">8</span></a>
                </li>
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
							<img class="rounded-circle" id="profile-image" src="assets/img/user.jpg" width="24" alt="Admin">
							<span class="status online"></span>
						</span>
                        <span id="profile-title">Admin</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item user-profile" href="profile">My Profile</a>
                        <a class="dropdown-item user-edit" href="edit-profile">Edit Profile</a>
                        <a class="dropdown-item" href="settings.html">Settings</a>
                        <a class="dropdown-item" href="/logout">Logout</a>
                    </div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item user-profile" href="profile">My Profile</a>
                    <a class="dropdown-item user-edit" href="edit-profile">Edit Profile</a>
                    <a class="dropdown-item" href="settings.html">Settings</a>
                    <a class="dropdown-item" href="/logout">Logout</a>
                </div>
            </div>
        </div>
        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">Main</li>
                        <li class="active">
                            <a href="/dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="/users"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
                        </li>
                        <li>
                            <a href="patients.html"><i class="fa fa-wheelchair"></i> <span>Patients</span></a>
                        </li>
                        <li>
                            <a href="appointments.html"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
                        </li>
                        <li>
                            <a href="schedule.html"><i class="fa fa-calendar-check-o"></i> <span>Doctor Schedule</span></a>
                        </li>
                        <li>
                            <a href="departments.html"><i class="fa fa-hospital-o"></i> <span>Departments</span></a>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="fa fa-user"></i> <span> Contents </span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="/movies/listing">Movies</a></li>
                                <li><a href="/shows/listing">Shows</a></li>
                                <li><a href="/seasons/listing">Seasons</a></li>
                                <li><a href="/episodes/listing">Episodes</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="fa fa-money"></i> <span> Accounts </span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="invoices.html">Invoices</a></li>
                                <li><a href="payments.html">Payments</a></li>
                                <li><a href="expenses.html">Expenses</a></li>
                                <li><a href="taxes.html">Taxes</a></li>
                                <li><a href="provident-fund.html">Provident Fund</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="fa fa-book"></i> <span> Payroll </span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="salary.html"> Employee Salary </a></li>
                                <li><a href="salary-view.html"> Payslip </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="chat.html"><i class="fa fa-comments"></i> <span>Chat</span> <span class="badge badge-pill bg-primary float-right">5</span></a>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="fa fa-envelope"></i> <span> Email</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="compose.html">Compose Mail</a></li>
                                <li><a href="inbox.html">Inbox</a></li>
                                <li><a href="mail-view.html">Mail View</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="assets.html"><i class="fa fa-cube"></i> <span>Assets</span></a>
                        </li>
                        <li>
                            <a href="activities.html"><i class="fa fa-bell-o"></i> <span>Activities</span></a>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="fa fa-flag-o"></i> <span> Reports </span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="expense-reports.html"> Expense Report </a></li>
                                <li><a href="invoice-reports.html"> Invoice Report </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="settings.html"><i class="fa fa-cog"></i> <span>Settings</span></a>
                        </li>
                        <li class="menu-title">Extras</li>
                        <li class="submenu">
                            <a href="#"><i class="fa fa-columns"></i> <span>Pages</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="login"> Login </a></li>
                                <li><a href="register"> Register </a></li>
                                <li><a href="forgot-password"> Forgot Password </a></li>
                                <li><a href="change-password2.html"> Change Password </a></li>
                                <li><a href="lock-screen.html"> Lock Screen </a></li>
                                <li><a href="profile.html"> Profile </a></li>
                                <li><a href="gallery.html"> Gallery </a></li>
                                <li><a href="error-404.html">404 Error </a></li>
                                <li><a href="error-500.html">500 Error </a></li>
                                <li><a href="blank-page.html"> Blank Page </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title">Movie View</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="blog-view"><?php echo body($movieDetails); ?>
                            <div class="widget blog-share clearfix">
                                <h3>Share the post</h3>
                                <ul class="social-share">
                                    <li><a href="#." title="Facebook"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#." title="Twitter"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#." title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a href="#." title="Google Plus"><i class="fa fa-google-plus"></i></a></li>
                                    <li><a href="#." title="Youtube"><i class="fa fa-youtube"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <aside class="col-md-4">
                        <div class="widget search-widget">
                            <h5>Movie Form</h5><?php echo form($movieDetails); ?>
                        </div>
                        <?php echo productionCompanies($movieDetails); ?>
                        <?php echo otherInfo($movieDetails); ?>
                        <?php echo tags($movieDetails); ?>
                    </aside>
                </div>
            </div>
            <div class="notification-box">
                <div class="msg-sidebar notifications msg-noti">
                    <div class="topnav-dropdown-header">
                        <span>Messages</span>
                    </div>
                    <div class="drop-scroll msg-list-scroll" id="msg_list">
                        <ul class="list-box">
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">R</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author">Richard Miles </span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item new-message">
                                        <div class="list-left">
                                            <span class="avatar">J</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author">John Doe</span>
                                            <span class="message-time">1 Aug</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">T</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author"> Tarah Shropshire </span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">M</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author">Mike Litorus</span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">C</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author"> Catherine Manseau </span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">D</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author"> Domenic Houston </span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">B</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author"> Buster Wigton </span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">R</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author"> Rolland Webber </span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">C</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author"> Claire Mapes </span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">M</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author">Melita Faucher</span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">J</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author">Jeffery Lalor</span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">L</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author">Loren Gatlin</span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                            <span class="avatar">T</span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author">Tarah Shropshire</span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="topnav-dropdown-footer">
                        <a href="chat.html">See all messages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sidebar-overlay" data-reff=""></div>
    <script src="https://dashboard.streamstudios.online/assets/js/jquery-3.2.1.min.js"></script>
	<script src="https://dashboard.streamstudios.online/assets/js/popper.min.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/bootstrap.min.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/jquery.slimscroll.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/app.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/dashboard/users.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/dashboard/alerts.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/dashboard/movies_listing.js"></script>
</body>


<!-- blog-details23:56-->
</html>