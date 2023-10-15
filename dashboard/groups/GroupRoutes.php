<?php

namespace groups;

use GlobalsFunctions\Globals;

class GroupRoutes
{

    public function __construct(private readonly array $routes)
    {
    }

    public function startDashBoard(string $currentURL)
    {
        $currentURL = trim($currentURL, "/");
        $user = Globals::user()[0] ?? [];

        if(!empty($user) && empty($currentURL))
        {
            $route = self::findRoute("dashboard");
            require_once $route['file'];
            exit;
        }
        if(empty($user) && empty($currentURL))
        {
           Globals::redirect("/login");
           exit;
        }

        $route = self::findRoute($currentURL);
        if(!empty($route['route']) && !empty($route['file']) && !empty($route['access']))
        {
            if($route['access'] === "private" && !empty($user))
            {
                require_once $route['file'];
                exit;
            }
            if($route['access'] === "private" && empty($user))
            {
                Globals::redirect("/404");
                exit;
            }
            if($route['access'] === "public")
            {
                require_once $route['file'];
                exit;
            }
        }else{
            Globals::redirect("/404");
            exit;
        }
    }

    public function findRoute($route): array
    {
        foreach ($this->routes as $key=>$ROUTE)
        {
            if($ROUTE['route'] === $route)
            {
                return $ROUTE;
            }
        }
        return [
            'route'=>'404',
            'access'=>'public',
            'file'=>'templates/error-404.php'
        ];
    }

}