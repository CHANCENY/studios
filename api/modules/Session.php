<?php

namespace modules;

use ApiHandler\ApiHandlerClass;
use FormViewCreation\Logging;
use Json\Json;

class Session
{
  public function createSession(): array
  {
      $incomingJson = ApiHandlerClass::getPostBody();
      if(!empty($incomingJson))
      {
          $username = $incomingJson['username'];
          $password = $incomingJson['password'];

          if(!empty($username) && !empty($password))
          {
              $password = htmlspecialchars(strip_tags($password));
              $username = htmlspecialchars(strip_tags($username));

              $alreadyLoggedIn = hash("sha256", $username);
              if((new Token())->verifyToken($alreadyLoggedIn))
              {
                  return [
                      'status'=>404,
                      'link'=>'/api/tokens/token',
                      'msg'=> "You already logged in visit @link value endpoint to get your token if you lost it."
                  ];
              }

              $accepted = Logging::signingIn($password, ['mail'=>$username]);

              if($accepted === true)
              {
                  $timeStamp = time();
                  $tokenID = Json::uuid();
                  $line = $tokenID."@".$timeStamp;
                  $hash = hash("sha256",$line);
                  (new Token())->saveToken($hash,['time'=>$timeStamp, 'token'=>$tokenID, 'hash'=>$line, 'user'=>$username."|".password_hash($password,PASSWORD_BCRYPT)]);
                  (new Token())->saveToken($alreadyLoggedIn, ['hash'=>$hash]);
                  return [
                      'status'=>200,
                      's_key'=>$hash,
                      'msg'=> "You have successfully logged in. please always remember to set s-key header for all endpoints that requires s-key header thank you."
                  ];
              }
          }else{
              return ["status"=>401, "msg"=>"username or password not provided"];
          }
      }
      return [
          'status'=>406,
          "msg"=>"To create session you need to make post request to /api/session/create with json body data containing username and password using to login @https://streamstudios.online"
      ];
  }

  public function closeSession(): array
  {
      $s_key = ApiHandlerClass::findHeaderValue("s-key");
      if(!empty($s_key)){
          if((new Token())->destroyToken($s_key)){
              return [
                  'status'=>200,
                  'msg'=>"Session closed"
              ];
          }
      }
      return [
          'status'=>406,
          'msg'=>"Failed s-key header is missing value or is invalid"
      ];
  }

  public function regenerateSession(): array
  {
      $s_key = ApiHandlerClass::findHeaderValue('s-key');
      if(!empty($s_key))
      {
        if((new Token())->verifyToken($s_key)){
            $session_value = (new Token())->getTokenInfo($s_key);
            $id = (new Token())->getID($s_key);
            $newtime = time();
            $tokenID = Json::uuid();
            $line = $tokenID."@".$newtime;
            $hash = hash("sha256",$line);
            (new Token())->updateToken($id,$hash,['time'=>$newtime, 'token'=>$tokenID, 'hash'=>$line, 'user'=>$session_value['user']]);
            $username = explode('|', $session_value['user'])[0] ?? null;
            $alreadyLoggedIn = hash("sha256", $username);
            $id = (new Token())->getID($alreadyLoggedIn);
            (new Token())->updateToken($id,$alreadyLoggedIn ,['hash'=>$hash]);

            return [
                'status'=>200,
                's_key'=>$hash,
                'msg'=> "You have successfully regenerate session. please always remember to set s-key header for all endpoints that requires s-key header thank you."
            ];

        }
      }
      return [
          'status'=>406,
          'msg'=>"Failed to regenerate session (s-key) due to missing current s-key"
      ];
  }

}