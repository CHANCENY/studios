<?php

use GlobalsFunctions\Globals;

if(!empty(Globals::get("country_code")))
{
    $code = Globals::get("country_code");
    $states = \Modules\CountriesModular::getStateByCountry($code);
    if(!empty($states))
    {
        http_response_code(200);
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['results'=>array_values($states)]);
        exit;
    }
    echo \ApiHandler\ApiHandlerClass::stringfiyData(['results'=>[]]);
    exit;
}
