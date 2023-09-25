<?php
namespace modules;
use ApiHandler\ApiHandlerClass;
use Datainterface\Database;
use Datainterface\Delete;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Selection;
use Datainterface\Updating;

class Token
{
    public function __construct()
    {
        $column = ['token', 'data', 'tid'];
        $attributes = [
            'tid'=>['int(11)', 'auto_increment', 'primary key'],
            'data'=>['text', 'not null'],
            'token'=>['varchar(250)', 'not null']
        ];
        (new MysqlDynamicTables())->resolver(
            Database::database(),
            $column,
            $attributes,
            'api_tokens',
            false
        );

    }

    public function collectToken(): array
    {
        $bodyData = ApiHandlerClass::getPostBody();
        if(!empty($bodyData))
        {
            $username = $bodyData['username'] ?? null;
            if(!empty($username))
            {
                $alreadyLoggedIn = hash("sha256", $username);
                if((new Token())->verifyToken($alreadyLoggedIn))
                {
                    return [
                        'status'=>200,
                        's-key'=>(new Token())->getTokenInfo($alreadyLoggedIn)['hash'] ?? null,
                        'msg'=>"Your s-key found successfully"
                    ];
                }
            }
        }
        return [
            'status'=>406,
            'msg'=>"Failed to collect your s-key token due to missing username or invalid username"
        ];
    }

    public function saveToken($token, $data): bool
    {
        return Insertion::insertRow('api_tokens', ['token'=>$token, 'data'=>json_encode($data)]);
    }

    public function verifyToken($token): bool
    {
        return !empty(Selection::selectById('api_tokens', ['token' => $token]));
    }

    public function destroyToken($token): bool
    {
        $data = Selection::selectById('api_tokens',['token'=>$token]);
        if(!empty($data)){
            $d = json_decode($data[0]['data'], true);
            $user = explode("|", $d['user'])[0];
            $alreadyLoggedIn = hash("sha256",$user);
            Delete::delete('api_tokens',['token'=>$alreadyLoggedIn]);
        }
        return Delete::delete('api_tokens',['token'=>$token]);
    }

    public function updateToken($id, $newToken, $data): bool
    {
        return Updating::update('api_tokens',['token'=>$newToken, 'data'=>json_encode($data)],['tid'=>$id]);
    }

    public function getTokenInfo($token): array
    {
        $data = Selection::selectById('api_tokens', ['token' => $token]);
        if(!empty($data))
        {
            return json_decode($data[0]['data'], true) ?? [];
        }
        return [];
    }

    public function getID($token): int
    {
        $data = Selection::selectById('api_tokens', ['token' => $token]);
        if(!empty($data))
        {
            return $data[0]['tid'] ?? 0;
        }
        return 0;
    }

}