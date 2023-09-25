<?php

namespace middle_security;
use ApiHandler\ApiHandlerClass;
use FormViewCreation\Logging;
use GlobalsFunctions\Globals;

class Token
{
    private mixed $token;
    private int $error;
    /**
     * @var true
     */
    private bool $accessGranted;

    /**
     * @return mixed
     */
    public function getToken(): mixed
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getRoute(): mixed
    {
        return $this->route;
    }
    private mixed $route;

    public function tokenize($headers): false|Token
    {
        $token = $headers::findHeaderValue('d-token');
        if(empty($token)) {
            $this->error = 403;
            $this->route = '/';
            return false;
        }
        $this->token = $token;
        $this->error = 200;
        return $this;
    }

    public function verifyToken(): false|Token
    {
        $decode_token = base64_decode($this->token);
        if(!empty($decode_token)){

            $listing = explode('-', $decode_token);
            if(isset($listing[0]) && isset($listing[1]) && isset($_SESSION['dashboard_ips'])){
                $tokenIP = $listing[0];
                $tokenTimeStamp = $listing[1];
                $timeStampInSessions = $_SESSION['dashboard_ips'][$tokenIP]['time'] ?? null;
                $currentTime = time();
                $currentIp = $this->remoteIP();

                if(is_null($timeStampInSessions))
                {

                    $this->error = 403;
                    $this->route = '403';
                    $this->accessGranted = false;
                    return false;
                }
                if($currentTime - $timeStampInSessions >= 3600)
                {

                    $this->error = 200;
                    $this->route = '/logout-dashboard';
                    $this->accessGranted = true;
                    return $this;
                }
                if($timeStampInSessions !== intval($tokenTimeStamp))
                {
                    $this->error = 403;
                    $this->route = '403';
                    $this->accessGranted = false;
                    return false;
                }
                if($currentIp !== $tokenIP){
                    $this->error = 403;
                    $this->route = '403';
                    $this->accessGranted = false;
                    return false;
                }
                $this->error  = 200;
                $this->route = Globals::uri();
                $this->accessGranted = true;
                return $this;
            }
            return false;
        }
        return false;
    }


    public function auth(string $username, string $password): false|Token
    {
        $accepted = Logging::signingIn($password, ['mail' => $username]);
        if ($accepted) {
            $this->error = 200;
            $this->route = 'login-dashboard';
            $time = time();
            $ip = $this->remoteIP();
            $_SESSION['dashboard_ips'][$ip] = ['time' => $time];
            $this->token = base64_encode("$ip-$time");
            return $this;
        }
        return false;
    }

    public function unAuth():false|Token
    {
        $decode_token = base64_decode($this->token);
        if(!empty($decode_token)){
            $listing = explode('-', $decode_token);
            $tokenIP = $listing[0] ?? '-00000000000000';
            if(isset($_SESSION['dashboard_ips'][$tokenIP])){
                unset($_SESSION['dashboard_ips'][$tokenIP]);
                $this->error = 200;
                $this->route = 'logout-dashboard';
            }
        }
        return false;
    }

    public function remoteIP(): string
    {
        // Function to get the user IP address
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function isMethod($currentURL): bool|null
    {
        $method = Definitions::METHODS[$currentURL] ?? null;
        if(is_null($method)){
            return null;
        }
        if(Globals::method() === $method){
            return true;
        }
        return false;
    }

    public function isAccessible($currentURL): bool
    {
        $access = Definitions::ACCESS[$currentURL] ?? null;
        if($access === 'public'){
            return true;
        }

        if($this->accessGranted){
            return true;
        }

        return false;
    }
}