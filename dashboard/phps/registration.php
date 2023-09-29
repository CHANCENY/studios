<?php

use GlobalsFunctions\Globals;
$message = "";
if(Globals::method() === "POST" && !empty(Globals::post("signUpdash")))
{
    $mail = Globals::post('mail') ?? null;
    $password = Globals::post("password") ?? null;
    $phone = Globals::post("phone") ?? null;

    /**
     * checking all set
     */
    if(!empty($mail) && !empty($phone) && !empty($password) && !empty(Globals::post("agreement")))
    {
        $data['mail'] = $mail;
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        $data['firstname'] = "No First Name";
        $data['lastname'] = "No Last Name";
        $data['phone'] = $phone;
        $data['token'] = base64_encode(\Json\Json::uuid());
        $_SESSION['unverified_user'] = $data;
        $link = Globals::protocal()."://".Globals::serverHost()."/verification?token=".$data['token'];
        if(sendMail($mail, $link))
        {
            $message = "<p style='color: lightgreen; font-size: large;'>Mail sent to your <a href='mailto:$mail'>$mail</a> verify to continue thank you</p>";
        }else
        {
            $message = "<p style='color: red; font-size: large;'>Failed to save your information</p>";
        }
    }
}

function sendMail($mail, $link)
{
    $data = [
      "subject"=> "Verify Account",
      "message"=> "<p>Click to continue verification <a href='$link'>Continue</a></p>",
      "altbody"=> "Dashboard",
      "user"=>array($mail),
      "reply"=>false,
      "attached"=>false,
     ];
    return \Mailling\Mails::send($data, "notify");
}

?>
<!DOCTYPE html>
<html lang="en">
<!-- register24:03-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>Preclinic - Medical & Hospital - Bootstrap 4 Admin Template</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="main-wrapper  account-wrapper">
    <div class="account-page">
        <div class="account-center">
            <div class="account-box">
                <form method="POST" action="/register" class="form-signin">
                    <div class="account-logo">
                        <a href="/dashboard"><img src="assets/img/logo-dark.png" alt=""></a>
                    </div>
                    <div class="form-group"><?php echo $message ?? null; ?>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="mail" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="form-group checkbox">
                        <label>
                            <input name="agreement" type="checkbox"> I have read and agree the Terms & Conditions
                        </label>
                    </div>
                    <div class="form-group text-center">
                        <button name="signUpdash" value="sign" class="btn btn-primary account-btn" type="submit">Signup</button>
                    </div>
                    <div class="text-center login-link">
                        Already have an account? <a href="/login">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
<!-- register24:03-->
</html>
