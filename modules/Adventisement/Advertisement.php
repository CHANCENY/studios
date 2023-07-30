<?php

namespace Modules\Adventisement;

use Datainterface\Database;
use Datainterface\Delete;
use Datainterface\Insertion;
use Datainterface\mysql\SelectionLayer;
use Datainterface\MysqlDynamicTables;
use Json\Json;
use Sessions\SessionManager;

class Advertisement
{
    public function __construct()
    {
        (new MysqlDynamicTables())->resolver(
            Database::database(),
            ["card_name", "card_body","card_uuid"],
            [
                "card_name"=>['varchar(250)', 'not', 'null'],
                "card_body"=>['longblob', 'null'],
                "card_uuid"=>['varchar(250)', 'not null'],
            ],
            "advertisement_cards"
        );
    }

    public function getCards(): array
    {
        return (new SelectionLayer())->setTableName("advertisement_cards")->selectAll()->rows();
    }

    /**
     * @param $cardName Any name can be used
     * @param $cardHtml html template having placeholders these placeholder need to be unique eg @movie-name@
     * @param $data array of placeholders in cardHtml with value
     * @return string|false key
     */
    public function create(string $cardName, string $cardHtml, array $data): string|false
    {
        $modified = $cardHtml;
        foreach ($data as $key=>$value){
          $modified = str_replace($key, $value, $modified);
        }

        $data = [
            "card_name"=>$cardName,
            "card_body"=>$modified,
            "card_uuid"=>Json::uuid()
        ];
        if(Insertion::insertRow("advertisement_cards", $data)){
            return $data['card_uuid'];
        }
        return false;
    }

    public function deleteCard($card_uuid): bool
    {
        return Delete::delete("advertisement_cards",["card_uuid"=>$card_uuid]);
    }

    /**
     * @param $cardHtml
     * @return string key of data stored
     */
    public function tempStore($cardHtml): string
    {
        $key = Json::uuid();
        SessionManager::setSession($key, $cardHtml);
        return $key;
    }

}