<?php

namespace routes_handlers;

use middle_security\Token;

class Forbidden extends Finish
{
    public function __construct(private Token $tokenize)
    {
        $this->statusCode = $this->tokenize->getError();
        $this->message = "Forbidden route Accessed";
    }
}