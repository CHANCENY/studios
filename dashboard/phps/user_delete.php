<?php

if(\GlobalsFunctions\Globals::method() === "POST")
{
    $data = \ApiHandler\ApiHandlerClass::getPostBody();
    $uid = $data['uid'] ?? null;
    if(!is_null($uid))
    {
        $userDeleted = \Datainterface\Delete::delete("users", ['uid'=>$uid]);
        $moreDeleted = \Datainterface\Delete::delete("users_additional", ['uid'=>$uid]);
        http_response_code(200);
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['status'=>200]);
    }
    http_response_code(200);
    echo \ApiHandler\ApiHandlerClass::stringfiyData(['status'=>404]);
}
