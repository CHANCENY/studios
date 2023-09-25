<?php

namespace response_handlers;

use ApiHandler\ApiHandlerClass;

class Responses
{
    public function __construct(mixed $anyRouteObject)
    {
         if(is_object($anyRouteObject)){
             $class = get_class($anyRouteObject);
             // Get the fully qualified class name (including namespace)

              // Extract the namespace from the class name
             $namespace = substr($class, 0, strrpos($class, '\\'));
             if($namespace === 'routes_handlers'){
                 $anyRouteObject->response();
                 exit;
             }
         }
         http_response_code(404);
         header("Content-Type: application/json");
         echo ApiHandlerClass::stringfiyData(['status'=>404, "no response found"]);
    }
}