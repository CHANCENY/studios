<?php

namespace Modules\NewAlerts;

use Datainterface\Database;
use Datainterface\Insertion;
use Datainterface\mysql\InsertionLayer;
use Datainterface\MysqlDynamicTables;
use Datainterface\Query;
use Datainterface\Selection;
use GlobalsFunctions\Globals;
use Mailling\Mails;

class SubcriberNews
{
    public function __construct(private readonly string $event)
    {
        $this->storageDefinition();
    }

    public function storageDefinition(): void
    {
        (new MysqlDynamicTables())->resolver(
            Database::database(),
          ['event_id', 'event_name', 'event_description', 'event_sent_status'],
          [
              'event_id'=>['int(11)', 'auto_increment', 'primary key'],
              'event_name'=>['varchar(100)', 'not null'],
              'event_description'=>['text', 'not null'],
              'event_sent_status'=>['int(11)', 'null']
          ],
           'event_news',
            false
        );

        (new MysqlDynamicTables())->resolver(
            Database::database(),
            ['subscribe_id', 'subscribe_name', 'subscribe_mail'],
            [
                'subscribe_id'=>['int(11)', 'auto_increment', 'primary key'],
                'subscribe_name'=>['varchar(100)','not null'],
                'subscribe_mail'=>['varchar(250)', 'not null']
            ],
            'subscribers',
            false
        );

        (new MysqlDynamicTables())->resolver(
            Database::database(),
            ['event_sid', 'sid', 'evid'],
            [
                'event_sid'=>['int(11)', 'auto_increment', 'primary key'],
                'sid'=>['int(11)'],
                'evid'=>['int(11)']
            ],
            'news_event_sent',
            false
        );
    }

    public function saveEvent(string $description): bool
    {
        $groupAll = [
            'event_name'=>$this->event,
            'event_description'=>$description,
            'event_sent_status'=>0
        ];
        return (new InsertionLayer())->setTableName('event_news')->setData($groupAll)->insert()->id();
    }

    public static function sendNews(): bool
    {
        (new SubcriberNews(''));
        $sentEventIds = [];
        $subscribers = Selection::selectAll('subscribers');

        foreach ($subscribers as $key=>$value) {
            $subType = $value['subscribe_name'];
            $list = str_contains($subType, '|') ? explode('|', $subType) : $subType;
            $query = self::queries($list);
            $result = Query::query($query);

            foreach ($result as $k => $event) {
                $data['subject'] = "Stream studio Alert";
                $data['message'] = $event['event_description'];
                $data['altbody'] = Globals::serverHost();
                $data['user'] = [$value['subscribe_mail']];
                $data['reply'] = false;
                $data['attached'] = false;

                $q = "SELECT * FROM news_event_sent WHERE evid = {$event['event_id']} AND sid = {$value['subscribe_id']}";
                $r = Query::query($q);
                if(empty($r)){
                    if(Mails::send($data, 'news')){
                        $sent['sid'] = $value['subscribe_id'];
                        $sent['evid'] = $event['event_id'];
                        Insertion::insertRow('news_event_sent', $sent);
                        $sentEventIds[] = $event['event_id'];
                    }
                }

            }
        }

        $queries = "";
        foreach ($sentEventIds as $key=>$value)
        {
            $queries .= "UPDATE event_news SET event_sent_status = 1 WHERE event_id = $value;";
        }
        if(!empty($queries)){
            Query::query($queries);
        }
        return true;
    }

    public function subscribeNews(string $emailAddress): int
    {
        $subData = [
            'subscribe_name' =>$this->event,
            'subscribe_mail'=> $emailAddress
        ];
        if(empty(Selection::selectById('subscribers', ['subscribe_mail'=>$emailAddress])))
        {
            return Insertion::insertRow('subscribers',$subData);
        }

        return 0;
    }

    private static function queries($typesValue): string
    {
        $query = "SELECT * FROM event_news WHERE ";
        $line = "";
        if(gettype($typesValue) === 'array')
        {
            $l = "";
            foreach ($typesValue as $key=>$value){
                $l .= "'$value',";
            }
            $l = substr($l, 0, strlen($l) - 1);
            $line = " event_name IN ($l) AND event_sent_status = 0";
        }
        else
        {
            $line = " event_name = '$typesValue' AND event_sent_status = 0";
        }
        return $query.$line;
    }

}