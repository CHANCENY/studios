<?php

namespace app;


use ApiHandler\ApiHandlerClass;
use Handlers;
use modules;


class App
{
    private mixed $endpoints;
    private array $bodyData;
    private array $paramsData;

    /**
     * @return array
     */
    public function getParamsData(): array
    {
        return $this->paramsData;
    }

    /**
     * @return mixed
     */
    public function getEndpoints(): mixed
    {
        return $this->endpoints;
    }

    /**
     * @return array
     */
    public function getBodyData(): array
    {
        return $this->bodyData;
    }

    /**
     * @return mixed
     */
    public function getCallbackClass(): mixed
    {
        return $this->callbackClass;
    }

    /**
     * @return mixed
     */
    public function getCallbackFunction(): mixed
    {
        return $this->callbackFunction;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }
    private mixed $callbackClass;
    private mixed $callbackFunction;
    private $results;

    public function __construct()
    {
        $this->envLoader();
    }
    private function envLoader(): void
    {
        $endpoint_path = "config/endpoints.json";
        if(file_exists($endpoint_path)){
            $this->endpoints = json_decode(file_get_contents($endpoint_path), true);
        }else{
            $this->endpoints = [];
        }
    }
    public function start(string $endpoint): App
    {
        top:
        $foundEndpoint = [];
        foreach ($this->endpoints as $key=>$object)
        {
            // Escape special characters in the endpoint and convert it to a regular expression
            $end = str_starts_with($endpoint,'/') ? $endpoint : "/".$endpoint;
            $default = preg_quote($object['endpoint'], "/");

            // Add start and end anchors to the regular expression to ensure a full match
            $regex_endpoint = '/^' . $default . '$/';

            // Check if the current URI matches the endpoint using preg_match
            if (preg_match($regex_endpoint, $end)) {
                $foundEndpoint = $object;
                break; // Exit the loop since we found a match
            }

        }

        if(!empty($foundEndpoint))
        {
            // method
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            if($this->processRequirements($foundEndpoint, $requestMethod))
            {
                $class = $foundEndpoint['callback']['class'];
                $function = $foundEndpoint['callback']['function'];
                $obj =  $this->createObject($class);
                if(is_object($obj)){
                    // Get the namespace of the class
                    $className = get_class($obj);

                    // Extract the namespace
                    $namespace = substr($className, 0, strrpos($className, '\\'));
                    if(str_contains($namespace, "Handlers")){
                        $this->results = $obj->$function($this);
                        $_SESSION['statusCode'] = $this->results['status'] ?? 200;
                    }else{
                        $this->results = $obj->$function();
                        $_SESSION['statusCode'] = $this->results['status'] ?? 200;
                    }
                }
            }
        }else{
            $endpoint = "/api/error/404";
            goto top;
        }
        return $this;
    }

    private function processRequirements($endpointFound, $method): bool
    {

        if($endpointFound['method'] !== $method){
            $_SESSION['statusCode'] = 400;
            $this->results['msg'] = "Bad request";
            return false;
        }

        $options = $endpointFound['required-options'];
        $header = $options['header'] ?? [];
        $body = $options['body-keys'] ?? [];
        $params = $options['params-keys'] ?? [];

        //check headers
        if(empty($header)){
            if(!str_contains($endpointFound['access'],'anonymous')){
                $_SESSION['statusCode'] = 401;
                $this->results['msg'] = "Unauthorized";
                return false;
            }
        }

        foreach ($header as $key=>$headerKey)
        {
            $headerValue = ApiHandlerClass::findHeaderValue($headerKey);
            if(empty($headerValue))
            {
                $_SESSION['statusCode'] = 400;
                $this->results['msg'] = "Missing header value for $headerKey";
                return false;
            }
            if(str_contains($endpointFound['endpoint'],'api'))
            {
                if($headerKey === "Content-Type" && $headerValue !== "application/json")
                {
                    $_SESSION['statusCode'] = 400;
                    $this->results['msg'] = "Content type value $headerValue not allowed";
                    return false;
                }
                if($headerKey === "s-key" && str_contains($endpointFound['access'], "authenticated"))
                {
                    $set = (new modules\Token())->verifyToken($headerValue);
                    if($set === false){
                        $_SESSION['statusCode'] = 400;
                        $this->results['msg'] = "Missing header value for $headerKey or is invalid";
                        return false;
                    }
                }
            }
            if($endpointFound['access'] === "anonymous")
            {
                $key = ApiHandlerClass::findHeaderValue('s-key');
                $checked = (new modules\Token())->verifyToken($key ?? "");
                if(!empty($key) && $checked === true){
                    $_SESSION['statusCode'] = 404;
                    $this->results['msg'] = "Trying to access endpoint only visible to anonymous users";
                    return false;
                }
            }
        }

        //check callbacks
        $callbacks = $endpointFound['callback'];
        if(empty($callbacks) || empty($callbacks['class']) || empty($callbacks['function']))
        {
            return false;
        }
        $this->callbackClass = $callbacks['class'];
        $this->callbackFunction = $callbacks['function'];

        //check body
        $requestBodyDataSet = ApiHandlerClass::getPostBody();
        $this->bodyData = [];
        foreach ($body as $key=>$value){
            $this->bodyData[$value] = $requestBodyDataSet[$value] ?? null;
        }

        //check params
        $requestParamsSet = ApiHandlerClass::paramsQuery();
        $this->paramsData = [];
        foreach ($params as $key=>$param)
        {
            $this->paramsData[$param] = $requestParamsSet[$param] ?? null;
        }
        return true;
    }

    public function createObject($class): mixed
    {

        try {
            $fullName = "modules\\$class";
            return new $fullName();
        }catch (\Throwable $e)
        {
            $fullName = "Handlers\\$class";
            return new $fullName();
        }
    }
}