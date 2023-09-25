<?php

namespace routes_handlers;

use middle_security\Token;

class BadRequest extends Finish
{
    protected mixed $message = "Bad Request";
    protected int $statusCode = 400;
    public function __construct(private Token $tokenize)
    {
    }
}