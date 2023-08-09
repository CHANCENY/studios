<?php

namespace Modules\Modals;

class Debug
{

    public static function debug($data){
        echo "<pre style='color: white;'>";
        print_r($data);
       echo "</pre>";
    }
}