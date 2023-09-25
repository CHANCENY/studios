<?php

use Datainterface\Database;
use Datainterface\MysqlDynamicTables;
use GlobalsFunctions\Globals;

if(!empty(Globals::user()) || Globals::get("user"))
{
    $attr = [
        "country"=>['varchar(100)'],
        "state"=>['varchar(100)'],
        "zip"=>['int(11)'],
        "gender"=>['varchar(100)'],
        'birthday'=>['varchar(100)'],
        'uid'=>['int(11)']
    ];

    (new MysqlDynamicTables())->resolver(
        Database::database(),
        array_keys($attr),
        $attr,
        "users_additional"
    );

    $sql = "SELECT
            uu.firstname,
            uu.lastname,
            uu.mail,
            uu.phone,
            uu.address,
            uu.created,
            uu.verified,
            uu.blocked,
            uu.role,
            uu.uid,
            uu.image,
            COALESCE(addit.country, '') AS country,
            COALESCE(addit.state, '') AS state,
            COALESCE(addit.zip, '') AS zip,
            COALESCE(addit.gender, '') AS gender,
            COALESCE(addit.birthday, '') AS birthday
        FROM
            users AS uu
        LEFT JOIN
            users_additional AS addit
        ON
            addit.uid = uu.uid
        WHERE
            uu.uid = :userid";

    $user = Globals::get("user");
    if(empty($user))
    {
        $user = Globals::user()[0]['uid'] ?? 0;
    }
    $data = \Datainterface\Query::query($sql, ['userid'=>$user]);
    if(!empty($data))
    {
        $data = $data[0];
    }
    echo \ApiHandler\ApiHandlerClass::stringfiyData($data);
    exit;
}