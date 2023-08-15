<?php use Modules\Comments\Comments;

@session_start();
ob_clean();
ob_flush();

@session_start();

$data = \ApiHandler\ApiHandlerClass::getPostBody();

if(isset($data['likes']) && $data['likes'] === true)
{
    $save = new Comments(uid: $data['uid'],cid: $data['cid'],type: 0);
    $save->saveLikes();

    if($save->getError() === false){
        http_response_code(200);
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['status'=>$save->likes()]);
        exit;
    }else{
        http_response_code(200);
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['status'=>$save->likes()]);
        exit;
    }
}


if(isset($data) && $data['dislikes'] === true){
    $save = new Comments(uid: $data['uid'],cid: $data['cid'],type: 0);
    $save->saveDislikes();

    if($save->getError() === false){
        http_response_code(200);
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['status'=>$save->disLikes()]);
        exit;
    }else{
        http_response_code(200);
        echo \ApiHandler\ApiHandlerClass::stringfiyData(['status'=>$save->disLikes()]);
        exit;
    }
}