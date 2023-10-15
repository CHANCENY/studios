<?php

use GlobalsFunctions\Globals;
use Modules\Movies\Movie;

$movieID = Globals::get("movie-id");

global $moreLink;
$moreLink = null;

$movie = [];
if(empty($movieID))
{
    Globals::redirect("/movies/listing");
    exit;
}

$movie = (new \groups\GroupMovies())->movieToEdit(intval($movieID))[0] ?? [];

if(Globals::method() === "POST" && !empty(Globals::post("edit_movie")))
{
    $data = [];
    if(!empty(Globals::files("new_image")))
    {
        $oldUUID = explode("=", $movie['movie_image']);
        $oldUUID = end($oldUUID);
        $img = (new \groups\GroupMovies())->newImage(Globals::files('new_image'),$oldUUID,Globals::post("movie_id"));
        if($img)
        {
            $data['movie_image'] = $img;
        }
    }else{
        $data['movie_image'] = Globals::post("old_image");
    }

    $data['title'] = Globals::post("title") ?? $movie['title'];
    $data['url'] = Globals::post("url") ?? $movie['url'];
    $data['duration'] = Globals::post("duration") ?? $movie['duration'];

    $date = Globals::post("release_date");
    if(!empty(Globals::post("release_date")))
    {
        $date = str_replace("/", "-", $date);
        try {
            $date = (new DateTime($date))->format("Y-m-d");
        }catch (Throwable $e){
            $date = $movie['release_date'];
        }
    }
    $data['release_date'] = !empty($date) ? $date : $movie['release_date'];
    $data['description'] = Globals::post("overview") ?? $movie['description'];
    if((new Movie())->updateMovie($data, $movie['movie_id']))
    {
        (new \groups\Notifications())->movieUpdated($data['title']);
        Globals::redirect("/movies/listing");
        exit;
    }
}



function editMoviePage($movie)
{
    global $moreLink;
    $title = $movie['title'];
    $URL = $movie['url'];
    $date = $movie['release_date'];
    $description = $movie['description'];
    $duration = $movie['duration'];
    $image = $movie['movie_image'];
    $movieID = $movie['movie_id'];
    $uri = Globals::uri();
    if(!hasMoreAlready())
    {
        $moreLink = <<<EOD
                        <a href="/movies/add-movie?type=tmdb&title=$title&internal=$movieID" class="btn btn-primary float-right btn-rounded"><i class="fa fa-plus"></i> Add More Information</a>
EOD;
    }
    return <<<EOD
<!DOCTYPE html>
<html lang="en">
<!-- edit-employee24:07-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="https://dashboard.streamstudios.online/assets/img/favicon.ico">
    <title>Preclinic - Medical & Hospital - Bootstrap 4 Admin Template</title>
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/select2.min.css">
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
                            <a href="/users"><i class="fa fa-user-md"></i> <span>Users</span></a>
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
                    <div class="col-lg-8 offset-lg-2">
                        <h4 class="page-title">Edit Movie</h4>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form method="POST" action="$uri" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Movie Title <span class="text-danger">*</span></label>
                                        <input class="form-control" name="title" type="text" value="$title">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Movie URL</label>
                                        <input class="form-control" name="url" value="$URL" type="text">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Movie Duration <span class="text-danger">*</span></label>
                                        <input class="form-control" name="duration" value="$duration" type="text">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Movie Old Image <span class="text-danger">*</span></label>
                                        <input class="form-control" type="url" name="old_image" value="$image">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Movie ID <span class="text-danger">*</span></label>
                                        <input type="text" name="movie_id" value="$movieID" class="form-control">
                                    </div>
                                </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Movie New Image <span class="text-danger">*</span></label>
                                        <input type="file" name="new_image" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Movie Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" name="release_date" value="$date" type="text">
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Movie Overview<span class="text-danger">*</span></label>
                                        <textarea name="overview" cols="2" rows="2" class="form-control">$description</textarea>
                                    </div>
                                </div>
                            </div> 
                            <div class="m-t-20 text-center">
                                <button type="submit" name="edit_movie" value="em" class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
             <div class="content"><div class="row">$moreLink</div></div>
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
    <script src="https://dashboard.streamstudios.online/assets/js/select2.min.js"></script>
	<script src="https://dashboard.streamstudios.online/assets/js/moment.min.js"></script>
	<script src="https://dashboard.streamstudios.online/assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/app.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/dashboard/users.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/dashboard/alerts.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/dashboard/movies_listing.js"></script>
</body>


<!-- edit-employee24:07-->
</html>
EOD;

}


function hasMoreAlready(): bool
{
    $movie = Globals::get("movie-id");
    if(!empty($movie))
    {
        $data = \Datainterface\Query::query("SELECT additional_id FROM additional_information WHERE internal_id = :id AND bundle = :b", ['id'=>$movie, 'b'=>"movies"]);
        if(!empty($data))
        {
            return true;
        }
    }
    return false;
}

echo editMoviePage($movie);
