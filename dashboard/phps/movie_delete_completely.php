<?php


use GlobalsFunctions\Globals;

if(Globals::method() === "POST")
{
    $movieID = \ApiHandler\ApiHandlerClass::getPostBody()['movie'] ?? null;
    if(!empty($movieID))
    {
        if((new \groups\GroupMovies())->movieDelete($movieID))
        {
            $magic = new \groups\Notifications();
            $magic->movieDeleted($movieID);
            http_response_code(200);
            echo \ApiHandler\ApiHandlerClass::stringfiyData(['status'=>200]);
            exit;
        }
    }
    http_response_code(404);
    echo \ApiHandler\ApiHandlerClass::stringfiyData(['status'=>404]);
    exit;
}
