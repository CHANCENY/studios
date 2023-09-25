<?php

namespace modules;

class Error
{
  public function notFound(): mixed
  {
      return [
          "status"=>404,
          "msg"=>"Not Found"
      ];
  }
}