<?php

namespace Modules\Modals;

class BundleInvalidException extends \Exception
{
    protected  $message = "Invalid Bundle value given";

    protected $code = 342;
}