<?php use GlobalsFunctions\Globals;

@session_start();

if(!empty(Globals::user())){
    Globals::redirect('/home');
    exit;
}

if(empty(\GlobalsFunctions\Globals::get('token'))){
    echo \Alerts\Alerts::alert("warning", "Token is missing you can continue!");
}else{
    if(\FormViewCreation\RegisterUser::saveVerifiedUser(\GlobalsFunctions\Globals::get('token'))){
        \GlobalsFunctions\Globals::redirect("/login-user-at-stream-studios");
        exit;
    }
}
?>