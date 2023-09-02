<?php

use FormViewCreation\Logging;
use GlobalsFunctions\Globals;

ob_clean();
ob_flush();

@session_start();

$message = "";
if(Globals::method() === "POST" && !empty(Globals::post('sign-stream-studios')))
{

    $emailAddress = Globals::post('email');
    $password = Globals::post('password');

    $allChecks = true;

    if(empty($emailAddress)){
        $message .= "<p class='text-white-50'>Provide EmailAddress to sign in</p>";
        $allChecks = false;
    }

    if(empty($password)){
        $message .= "<p class='text-white-50'>Provide password to sign in</p>";
        $allChecks = false;
    }

    if($allChecks){
       $destination = Globals::get('destination');

       if(Logging::signingIn($password,['mail'=>$emailAddress])){
           if(!empty($destination)){
               Globals::redirect($destination);
           }else{
               Globals::redirect('home');
           }
       }else{
           $message .= "<p>Incorrect Username or password</p>";
       }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">

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

    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Dmitry Volkov">
    <title>FlixGo – Online Movies, TV Shows</title>

</head>
<body class="body">
<div class="sign section--bg" data-bg="assets/main/img/section/section.jpg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="sign__content">
                    <!-- authorization form -->
                    <form method="POST" action="<?php echo Globals::uri(); ?>" class="sign__form">
                        <a href="home" class="sign__logo">
                            <img src="assets/main/img/logo.svg" alt="">
                        </a>
                        <div class="sign__group" style="color: whitesmoke;"><?php echo $message ?? null; ?></div>
                        <div class="sign__group">
                            <input type="text" name="email" class="sign__input" placeholder="Email">
                        </div>

                        <div class="sign__group">
                            <input type="password" name="password" class="sign__input" placeholder="Password">
                        </div>

                        <div class="sign__group sign__group--checkbox">
                            <input id="remember" name="remember" type="checkbox" checked="checked">
                            <label for="remember">Remember Me</label>
                        </div>

                        <button name="sign-stream-studios" value="sign" class="sign__btn" type="submit">Sign in</button>

                        <span class="sign__text">Don't have an account? <a href="stream-studios-join">Sign up!</a></span>

                        <span class="sign__text"><a href="forgot-stream-studios-password">Forgot password?</a></span>
                    </form>
                    <!-- end authorization form -->
                </div>
            </div>
        </div>
    </div>
</div>
