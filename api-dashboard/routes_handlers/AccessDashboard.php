<?php

namespace routes_handlers;

use ApiHandler\ApiHandlerClass;
use middle_security\Token;

class AccessDashboard extends Finish
{
    public function __construct(private Token $tokenize)
    {
        $this->handleLoginProccess();
    }

    public function handleLoginProccess(): void
    {
        $username = ApiHandlerClass::getPostBody()['username'] ?? null;
        $password = ApiHandlerClass::getPostBody()['password'] ?? null;

        if (is_null($username) || is_null($password)){
            $this->statusCode = 401;
            $this->message = "Username or password is empty";
        }else{
            $security = $this->tokenize->auth($username, $password);
            if($security){
                $this->statusCode = $security->getError();
                $this->message = $security->getToken();
            }else{
                $this->statusCode = 401;
                $this->message = "Unauthorized wrong username or password";
            }
        }
    }
}