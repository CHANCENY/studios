<?php

use GlobalsFunctions\Globals;

@session_start();

if(empty(Globals::user())){
    Globals::redirect('/home');
    exit;
}
\FormViewCreation\Logging::signingOut();
\GlobalsFunctions\Globals::redirect('home');
exit;