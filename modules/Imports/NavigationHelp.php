<?php

namespace Modules\Imports;

use Datainterface\Database;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Selection;
use Modules\Renders\ImageHandler;


class NavigationHelp
{

    public function __construct(private readonly string $fileUUID = "")
    {
        $column = ['nvid','title', 'paragraphs', 'images', 'videos'];
        $attributes = [
            'nvid'=>['int(11)', 'auto_increment', 'primary key'],
            'title'=>['varchar(250)', 'not null'],
            'paragraphs'=>['text', 'null'],
            'images'=>['varchar(300)', 'null'],
            'videos'=>['varchar(300)', 'null']
        ];
        (new MysqlDynamicTables())->resolver(Database::database(),$column, $attributes, 'navigation_content_help', false);
    }

    public function saveNavigationContent(array $data): bool
    {
        if(Insertion::insertRow('navigation_content_help', $data)){
            return true;
        }
        return false;
    }

    public function findFile(): ImageHandler
    {
        return $image = (new ImageHandler($this->fileUUID))->loadImage();
    }

    public function contents(): array
    {
        return Selection::selectAll('navigation_content_help');
    }
}