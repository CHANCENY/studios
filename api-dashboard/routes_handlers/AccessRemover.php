<?php

namespace routes_handlers;

use middle_security\Token;

class AccessRemover extends Finish
{
    protected mixed $message = "Failed to logout";
    protected int $statusCode = 404;
    public function __construct(private readonly Token $tokenize)
    {
        $this->handleRequest();
    }

    public function handleRequest(): void
    {
        $t = $this->tokenize->unAuth();
        if($this->tokenize->getError() === 200){
            $this->message = "Logout out";
        }
        $this->statusCode = $this->tokenize->getError();
    }
}