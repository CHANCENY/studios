<?php

ob_clean();
ob_flush();

@session_start();

$index = \GlobalsFunctions\Globals::get('index');

$data = \Modules\Modals\Home::newPremierMovies();
if(!empty($data)){
    $chuncked = array_chunk($data,6);
    try{
        if(intval($index) > count($chuncked)){
            http_response_code(404);
            header("Content-Type: application/json");
            echo \ApiHandler\ApiHandlerClass::stringfiyData(["status"=>200]);
        }else{
            $result = $chuncked[$index];
            http_response_code(200);
            header("Content-Type: application/json");
            echo \ApiHandler\ApiHandlerClass::stringfiyData(["status"=>200, 'body'=>$result]);
        }
    }catch (\Throwable $e){
        http_response_code(500);
        header("Content-Type: application/json");
        echo \ApiHandler\ApiHandlerClass::stringfiyData(["status"=>500]);
    }

}else{
    http_response_code(404);
    header("Content-Type: application/json");
    echo \ApiHandler\ApiHandlerClass::stringfiyData(["status"=>404]);
}
exit;