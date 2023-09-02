<?php use GlobalsFunctions\Globals;

@session_start(); ?>

<?php


ob_clean();
ob_flush();

if(!empty(Globals::user())){
    Globals::redirect('/home');
    exit;
}

$message = "";
if(Globals::method() === "POST" && !empty(Globals::post('password-change')))
{
    $email = Globals::post('email');
    $password = Globals::post('password');
    $token = \Json\Json::uuid().\Json\Json::uuid();

    $_SESSION['changes'][$token] = ['password'=>$password, 'email'=>$email];
    $url = Globals::protocal()."://".Globals::serverHost().Globals::url()."?token=$token";
    $data['subject'] = "Reset Password";
    $data['attached'] = false;
    $data['reply'] = false;
    $data['user'] = [$email];
    $data['altbody'] = "Changing password @".Globals::protocal()."://".Globals::serverHost();
    $data['message'] = "<p>You have requested to change your password at Stream studios FLIXGO. please verify if it you.</p>";
    $data['message'] .= "<p>Please note that click this link will automatically reset your password</p>";
    $data['message'] .= "<a href='$url'>Click to change password</a>";

    if(\Mailling\Mails::send($data,'notify')){
        $message = "<p>We have sent the verification email to corresponding user</p>";
    }
}

if(!empty(Globals::get('token'))){
    $token = Globals::get('token');
    $data = $_SESSION['changes'][$token] ?? [];

    if(empty($data)){
        $message = "<p>Sorry your token is invalid</p>";
    }else{
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $email = $data['email'];

        if(\Datainterface\Updating::update('users',['password'=>$password], ['mail'=>$email]))
        {
            unset($_SESSION['changes'][$token]);
            Globals::redirect('/login-user-at-stream-studios');
            exit;
        }else{
            $message = "<p>Failed to Change password</p>";
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

                        <button name="password-change" value="change" class="sign__btn" type="submit">Reset Password</button>

                        <span class="sign__text">Don't have an account? <a href="stream-studios-join">Sign up!</a></span>
                    </form>
                    <!-- end authorization form -->
                </div>
            </div>
        </div>
    </div>
</div>

