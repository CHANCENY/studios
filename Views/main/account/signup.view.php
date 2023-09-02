<?php

use FormViewCreation\RegisterUser;
use GlobalsFunctions\Globals;

ob_clean();
ob_flush();

if(!empty(Globals::user())){
    Globals::redirect('/home');
    exit;
}


/**
 * new user
 */
$message = "";
if(Globals::method() === "POST" && !empty(Globals::post('new-user')))
{
    $name = Globals::post('fullname');
    $email = Globals::post('email');
    $password = Globals::post('password');

    if(!empty($name) && !empty($email) && !empty($password)){
        if(!validateEmail($email)){
            $message = "<p>Email of that type is not allowed</p>";
        }
        $names = explode(' ', $name);

        $data['mail'] = $email;
        $data['firstname'] = $names[0];
        $data['lastname'] = $names[1] ?? "";
        $data['password'] = $password;
        if(RegisterUser::registerUser($data, 'verify-emailaddress-new-user'))
        {
            $message = "<p>Your successfully submitted your information please verify your account. Email sent to $email</p>";
        }
    }
}

function validateEmail($email): bool
{
    $allowed = \functions\config('ALLOWED-MAILS');
    if(empty($allowed)){
        return true;
    }

    $list = explode(',', $allowed);
    $output = [];
    foreach ($list as $key=>$value){
        if(str_contains($email, $value)){
            $output[] = true;
        }else{
            $output[] = false;
        }
    }

    return !in_array(false, $output);
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
                    <!-- registration form -->
                    <form action="<?php echo Globals::url(); ?>" method="POST" class="sign__form">
                        <a href="home" class="sign__logo">
                            <img src="assets/main/img/logo.svg" alt="">
                        </a>
                        <div class="sign__group" style="color: whitesmoke;"><?php echo $message ?? null; ?>
                        </div>

                        <div class="sign__group">
                            <input type="text" name="fullname" class="sign__input" placeholder="Name">
                        </div>

                        <div class="sign__group">
                            <input type="email" name="email" class="sign__input" placeholder="Email">
                        </div>

                        <div class="sign__group">
                            <input type="password" name="password" class="sign__input" placeholder="Password">
                        </div>

                        <div class="sign__group sign__group--checkbox">
                            <input id="remember" name="remember" type="checkbox" checked="checked">
                            <label for="remember">I agree to the <a href="#">Privacy Policy</a></label>
                        </div>

                        <button class="sign__btn" name="new-user" value="new-user" type="submit">Sign up</button>

                        <span class="sign__text">Already have an account? <a href="login-user-at-stream-studios">Sign in!</a></span>
                    </form>
                    <!-- registration form -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="assets/main/js/jquery-3.3.1.min.js"></script>
<script src="assets/main/js/bootstrap.bundle.min.js"></script>
<script src="assets/main/js/owl.carousel.min.js"></script>
<script src="assets/main/js/jquery.mousewheel.min.js"></script>
<script src="assets/main/js/jquery.mCustomScrollbar.min.js"></script>
<script src="assets/main/js/wNumb.js"></script>
<script src="assets/main/js/nouislider.min.js"></script>
<script src="assets/main/js/plyr.min.js"></script>
<script src="assets/main/js/jquery.morelines.min.js"></script>
<script src="assets/main/js/photoswipe.min.js"></script>
<script src="assets/main/js/photoswipe-ui-default.min.js"></script>
<script src="assets/main/js/main.js"></script>
</body>

</html>