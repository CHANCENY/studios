<?php


use GlobalsFunctions\Globals;

function normalForm(): string
{
    return <<<EOD
 <div class="row">
                <div class="col-sm-12">
                    <form>
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Patient <span class="text-danger">*</span></label>
                                    <select class="select">
                                        <option>Please Select</option>
                                        <option>Charles Ortega</option>
                                        <option>Denise Stevens</option>
                                        <option>Jennifer Robinson</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Department <span class="text-danger">*</span></label>
                                    <select class="select">
                                        <option>Select Department</option>
                                        <option>Dentists</option>
                                        <option>Neurology</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="email">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Tax</label>
                                    <select class="select">
                                        <option>Select Tax</option>
                                        <option>VAT</option>
                                        <option>GST</option>
                                        <option>No Tax</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Patient Address</label>
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Billing Address</label>
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Invoice date <span class="text-danger">*</span></label>
                                    <div class="cal-icon">
                                        <input class="form-control datetimepicker" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Due Date <span class="text-danger">*</span></label>
                                    <div class="cal-icon">
                                        <input class="form-control datetimepicker" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-white">
                                        <thead>
                                        <tr>
                                            <th style="width: 20px">#</th>
                                            <th class="col-sm-2">Item</th>
                                            <th class="col-md-6">Description</th>
                                            <th style="width:100px;">Unit Cost</th>
                                            <th style="width:80px;">Qty</th>
                                            <th>Amount</th>
                                            <th> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <input class="form-control" type="text" style="min-width:150px">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" style="min-width:150px">
                                            </td>
                                            <td>
                                                <input class="form-control" style="width:100px" type="text">
                                            </td>
                                            <td>
                                                <input class="form-control" style="width:80px" type="text">
                                            </td>
                                            <td>
                                                <input class="form-control form-amt" readonly="" style="width:120px" type="text">
                                            </td>
                                            <td><a href="javascript:void(0)" class="text-success font-18" title="Add"><i class="fa fa-plus"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <input class="form-control" type="text" style="min-width:150px">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" style="min-width:150px">
                                            </td>
                                            <td>
                                                <input class="form-control" style="width:100px" type="text">
                                            </td>
                                            <td>
                                                <input class="form-control" style="width:80px" type="text">
                                            </td>
                                            <td>
                                                <input class="form-control form-amt" readonly="" style="width:120px" type="text">
                                            </td>
                                            <td><a href="javascript:void(0)" class="text-danger font-18" title="Remove"><i class="fa fa-trash-o"></i></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-white">
                                        <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">Total</td>
                                            <td style="text-align: right; padding-right: 30px;width: 230px">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-right">Tax</td>
                                            <td style="text-align: right; padding-right: 30px;width: 230px">
                                                <input class="form-control text-right form-amt" value="0" readonly="" type="text">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-right">
                                                Discount %
                                            </td>
                                            <td style="text-align: right; padding-right: 30px;width: 230px">
                                                <input class="form-control text-right" type="text">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="text-align: right; font-weight: bold">
                                                Grand Total
                                            </td>
                                            <td style="text-align: right; padding-right: 30px; font-weight: bold; font-size: 16px;width: 230px">
                                                $ 0.00
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Other Information</label>
                                            <textarea class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center m-t-20">
                            <button class="btn btn-grey submit-btn m-r-10">Save & Send</button>
                            <button class="btn btn-primary submit-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
EOD;

}

function tmdbSearchForm(): string
{
    $uri = Globals::uri();
    return <<<EOD
                    <div class="col-sm-6 col-md-6">
                    <form method="GET" action="$uri">
                        <div class="form-group form-focus">
                            <label class="focus-label">Show Title</label>
                            <input type="text" name="title" id="movie-filter-id" class="form-control floating">
                        </div>
                        <input type="hidden" name="type" value="tmdb">
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <button type="submit" class="btn btn-success btn-block"> Search </button>
                    </div>
                    </form>
                
EOD;

}

function buttons(): string{
    return <<<EOD
<div class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <a href="/shows/add-show?type=tmdb" class="btn btn-success btn-block"> TMDB SHOWS </a>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <a href="/shows/add-show?type=normal" class="btn btn-success btn-block"> NORMAL UPLOAD </a>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <a href="/shows/listing" class="btn btn-success btn-block"> CANCEL UPLOAD </a>
                    </div>
                </div>
EOD;

}

function actions(): string
{
    $type = Globals::get("type");
    if(empty($type))
    {
        return buttons();
    }
    if($type === "normal")
    {
        return normalForm();
    }
    if($type === "tmdb")
    {
        return tmdbSearchForm();
    }
    return "";
}

function tmdbSearchResults(): string
{
    $title = Globals::get("title");
    $foundMovies = [];
    if(!empty($title))
    {
        $title = str_replace(" ", "-", $title);

        for ($i = 1; $i <= 5; $i++)
        {
            $data = discoverShows($title, $i);
//            echo "<pre>";
//            print_r($data);
//            echo "</pre>";
//            exit;
            if(empty($data['error']))
            {
                $foundMovies[] = array_values($data['body']['results']) ?? [];
            }
        }
    }
    $card = null;

    foreach ($foundMovies as $key=>$value)
    {
        if(gettype($value) === "array")
        {
            foreach ($value as $k=>$v)
            {
                $overview = substr($v['overview'], 0, 124) ?? null;
                $mtitle = $v['name'] ?? $v['original_name'] ?? null;
                $date =  (new \DateTime($v['first_air_date']))->format("F d, Y");
                $id = $v['id'] ?? null;
                $vote = $v['vote_average'] ?? 0.0;
                $vote = intval($vote);
                $popularity = $v['popularity'] ?? 0;
                $count = $v['vote_count'] ?? 0;
                $image = $v['poster_path'] ?? $v['backdrop_path'] ?? null;
                $image = "https://image.tmdb.org/t/p/w92".$image;
                $internal = Globals::get("internal");
                if(!empty($internal))
                {
                    $internal = "/shows/additional-separate?show-id=$id&internal=$internal";
                    $count = "Save";
                }

                $card .= <<<ESP
<div class="col-sm-6 col-md-6 col-lg-4">
                        <div class="blog grid-blog">
                            <div class="blog-image">
                                <a href="/shows/show-details?show-id=$id"><img class="img-fluid" src="$image" alt=""></a>
                            </div>
                            <div class="blog-content">
                                <h3 class="blog-title"><a href="/shows/show-details?show-id=$id">$mtitle</a></h3>
                                <p>$overview</p>
                                <a href="/shows/show-details?show-id=$id" class="read-more"><i class="fa fa-long-arrow-right"></i> Read More</a>
                                <div class="blog-info clearfix">
                                    <div class="post-left">
                                        <ul>
                                            <li><a href="#."><i class="fa fa-calendar"></i> <span>$date</span></a></li>
                                        </ul>
                                    </div>
                                    <div class="post-right"><a href="#."><i class="fa fa-heart-o"></i>$vote</a> <a href="#."><i class="fa fa-eye"></i>$popularity</a> <a href="$internal"><i class="fa fa-comment-o"></i>$count</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
ESP;
            }
        }
    }

    return <<<EOD
 <div class="content">
                <div class="row">
                    <div class="col-sm-8 col-4">
                        <h4 class="page-title">Results</h4>
                    </div>
                </div>
                <div class="row">
                    $card
                </div>
            </div>
EOD;
}

$displaying = actions();

$tmdbResults = null;
if(Globals::get("type") === "tmdb" && !empty(Globals::get("title")))
{
    $tmdbResults = tmdbSearchResults();
}

function discoverShows(string $search, $page=1): array
{$authToken = \functions\config('TMDB');

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.themoviedb.org/3/search/tv?query=$search&include_adult=false&language=en-US&page=$page",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: $authToken",
            "accept: application/json"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    return [
        'error'=>$err,
        'body'=>json_decode($response, true)
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<!-- create-invoice24:07-->
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
                <div class="col-sm-12">
                    <h4 class="page-title">Create Show</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12"><?php echo $displaying; ?></div>
            </div>
        </div><?php echo $tmdbResults; ?>
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
<script src="https://dashboard.streamstudios.online/assets/js/moment.min.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/select2.min.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/app.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/dashboard/users.js"></script>
<script src="https://dashboard.streamstudios.online/assets/js/dashboard/alerts.js"></script>
</body>
<!-- create-invoice24:07-->
</html>