<?php

namespace routes_handlers;

use ApiHandler\ApiHandlerClass;


class Finish
{
    protected int $statusCode = 403;
    protected mixed $message = "Forbidden";

    public function response(): void
    {
        http_response_code($this->statusCode);
        header("Content-Type: application/json");
        echo ApiHandlerClass::stringfiyData(['status'=>$this->statusCode, 'results'=>$this->message]);
    }
}