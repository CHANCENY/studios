<?php

use GlobalsFunctions\Globals;

$showID = Globals::get("show-id") ?? null;
if(empty($showID))
{
   Globals::redirect("/shows/listing");
   exit;
}

$show = new \groups\GroupShows();
$show->loadForEdit(intval($showID));

if(Globals::method() === "POST" && !empty(Globals::post("show_edit")))
{
    $data['show_image'] = !empty(Globals::post("new_image")) ? Globals::post("new_image") : $show->image();
    $data['title'] = Globals::post("title") ?? $show->title();
    if(!empty(Globals::post("release_date")))
    {
        try {
            $data['release_date'] = (new DateTime(str_replace("/","-",Globals::post("release_date"))))->format("d-m-Y");
        }catch (Throwable $e){
            $data['release_date'] = $show->date();
        }
    }else{
        $data['release_date'] = $show->date();
    }
    $data['description'] = Globals::post("show_overview") ?? $show->overview();
    if(\Datainterface\Updating::update("tv_shows",$data, ['show_id'=>$showID]))
    {
        Globals::redirect("/shows/listing");
        exit;
    }
    //	2023-08-18
}

?>
<!DOCTYPE html>
<html lang="en">


<!-- tabs23:58-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="https://dashboard.streamstudios.online/assets/img/favicon.ico">
    <title>Preclinic - Medical & Hospital - Bootstrap 4 Admin Template</title>
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/bootstrap-datetimepicker.min.css">
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
                    <h4 class="page-title">TV Shows Edit</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="card-title"><?php echo $show->title(); ?></h4>
                        <ul class="nav nav-tabs nav-tabs-solid nav-justified">
                            <li class="nav-item"><a class="nav-link active" href="#solid-justified-tab1" data-toggle="tab">Shows</a></li>
                            <li class="nav-item"><a class="nav-link" href="#solid-justified-tab2" data-toggle="tab">Profile</a></li>
                            <li class="nav-item"><a class="nav-link" href="#solid-justified-tab3" data-toggle="tab">Messages</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="solid-justified-tab1">
                                <div class="content">
                                    <div class="row">
                                        <div class="col-lg-8 offset-lg-2">
                                            <h4 class="page-title">Edit Show</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8 offset-lg-2">
                                            <form method="POST" id="show-edit-form" action="<?php echo Globals::uri(); ?>" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Show Title</label>
                                                            <input class="form-control" value="<?php echo $show->title(); ?>" name="title" id="title-show" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Old Image</label>
                                                            <input class="form-control" readonly value="<?php echo $show->image(); ?>" name="old_image" id="od-image-show" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Release Date</label>
                                                            <div class="cal-icon">
                                                                <input class="form-control datetimepicker" value="<?php echo $show->date(); ?>" name="release_date" id="release-date-show" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Show Overview</label>
                                                                <textarea cols="2" rows="2" class="form-control" name="show_overview" id="overview-show"><?php echo $show->overview(); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>New Image</label>
                                                            <input class="form-control" name="new_image_file" onchange="uploadShowImage()" id="new-image-show" type="file">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="attach-files">
                                                    <ul>
                                                        <li>
                                                            <img id="old-image-preview" src="<?php echo $show->image(); ?>" alt="<?php echo $show->title(); ?>">
                                                            <a href="#" class="fa fa-close file-remove"></a>
                                                        </li>
                                                        <li>
                                                            <img id="new-image-preview" src="https://dashboard.streamstudios.online/assets/img/user.jpg" alt="">
                                                            <a href="#" class="fa fa-close file-remove"></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="m-t-20 text-center">
                                                    <button type="submit" name="show_edit" value="se" class="btn btn-primary submit-btn">Save Show</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="solid-justified-tab2">
                                Tab content 2
                            </div>
                            <div class="tab-pane" id="solid-justified-tab3">
                                Tab content 3
                            </div>
                        </div>
                    </div>
                </div>
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
<script src="https://dashboard.streamstudios.online/assets/js/select2.min.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/app.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/moment.min.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/dashboard/users.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/dashboard/alerts.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/dashboard/shows_listing.js"></script>
</body>


<!-- tabs23:59-->
</html>
