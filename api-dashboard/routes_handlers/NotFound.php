<?php

namespace routes_handlers;

use middle_security\Token;

class NotFound extends Finish
{
    protected mixed $message = "Route not found";
    protected int $statusCode = 404;
   public function __construct(private Token $tokenize)
   {
   }
}