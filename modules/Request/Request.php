<?php

namespace Modules\Request;

use Json\Json;

class Request
{
    public function saveRequest($data, $type): bool
    {
        $storage = "request/$type/request_file.json";
        $json = new Json();
        $json->setStoreName($storage);
        return $json->save($data)->isError();
    }
}