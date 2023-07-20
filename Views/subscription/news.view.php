<?php

try{
    $r = \Modules\NewAlerts\SubcriberNews::sendNews();
    echo \ApiHandler\ApiHandlerClass::stringfiyData(['sent'=>$r]);
    exit;
}catch (Throwable $e){
    echo \ApiHandler\ApiHandlerClass::stringfiyData(['sent'=>0]);
    exit;
}
