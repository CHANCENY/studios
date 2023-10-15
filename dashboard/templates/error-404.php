<?php
$link = null;
if(empty(\GlobalsFunctions\Globals::user()[0]))
{
    $link = <<<LINK
<a href="/login" class="btn btn-primary go-home">Go to Login</a>
LINK;
}else{
    $link = <<<LINK
<a href="/dashboard" class="btn btn-primary go-home">Go to Home</a>
LINK;
}
?>
<!DOCTYPE html>
<html lang="en">
<!-- error-40424:04-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="https://dashboard.streamstudios.online/assets/img/favicon.ico">
    <title>Preclinic - Medical & Hospital - Bootstrap 4 Admin Template</title>
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://dashboard.streamstudios.online/assets/css/style.css">
    <!--[if lt IE 9]>
		<script src="https://dashboard.streamstudios.online/assets/js/html5shiv.min.js"></script>
		<script src="https://dashboard.streamstudios.online/assets/js/respond.min.js"></script>
	<![endif]-->
</head>

<body>
    <div class="main-wrapper error-wrapper">
        <div class="error-box">
            <h1>404</h1>
            <h3><i class="fa fa-warning"></i> Oops! Page not found!</h3>
            <p>The page you requested was not found.</p><?php echo $link; ?>
        </div>
    </div>
    <script src="https://dashboard.streamstudios.online/assets/js/jquery-3.2.1.min.js"></script>
	<script src="https://dashboard.streamstudios.online/assets/js/popper.min.js"></script>
    <script src="https://dashboard.streamstudios.online/assets/js/bootstrap.min.js"></script>
</body>


<!-- error-40424:04-->
</html>