<?php

use GlobalsFunctions\Globals;

if(Globals::method() === "POST"
&& !empty(Globals::post("login-dashboards")))
{

    $username = Globals::post("username");
    $password = Globals::post("password");
    if(!empty($username) && !empty($password))
    {
        $checked = \FormViewCreation\Logging::signingIn($password, ['mail'=>$username]);
        if($checked === true)
        {
            $token = tokeinze($username, $password);
            if(!empty($token)){
                setcookie("token_skey", $token);
                $_SESSION['token_skey'] = $token;
                Globals::redirect("dashboard");
                exit;
            }
        }
    }
}


if(Globals::method() === "GET" && Globals::uri() === "/logout")
{
    if(destroyToken($_SESSION['token_skey']))
    {
        \FormViewCreation\Logging::signingOut();
        setcookie("token_skey", "", time() - 3600);
        unset($_COOKIE['token_skey']);
        Globals::redirect("login");
        exit;
    }else{
        Globals::redirect('dashboard');
        exit;
    }
}

function tokeinze($username, $password): string
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.streamstudios.online/api/session/create',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(['username'=>$username, 'password'=>$password]),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response, true);
    return $json['s_key'] ?? "";
}

function destroyToken($token): bool
{

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.streamstudios.online/api/session/close',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => array(
            "s-key: $token",
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $d = json_decode($response, true);
    if(isset($d['status'])  &&  $d['status'] === 200)
    {
        return true;
    }

    return false;
}

?>
<!DOCTYPE html>
<html lang="en">
<!-- login23:11-->
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
    <div class="main-wrapper account-wrapper">
        <div class="account-page">
			<div class="account-center">
				<div class="account-box">
                    <form method="POST" action="/login" class="form-signin">
						<div class="account-logo">
                            <a href="/dashboard"><img src="assets/img/logo-dark.png" alt=""></a>
                        </div>
                        <div class="form-group">
                            <label>Username or Email</label>
                            <input type="text" name="username" id="username" autofocus="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group text-right">
                            <a href="/forgot-password">Forgot your password?</a>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" value="c" name="login-dashboards" id="login-submit" class="btn btn-primary account-btn">Login</button>
                        </div>
                        <div class="text-center register-link">
                            Don’t have an account? <a href="register">Register Now</a>
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


<!-- login23:12-->
</html>