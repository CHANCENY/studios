<?php

namespace Modules\TypeModerator;

use Datainterface\Database;
use Datainterface\Delete;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Query;
use Datainterface\Selection;
use Datainterface\Updating;

class TypeModerator
{
    private array $newGroup;
    private array $groupId;

    private int $result;

    /**
     * @return array
     */
    public function getNewGroup(): array
    {
        return $this->newGroup;
    }

    /**
     * @return array
     */
    public function getGroupId(): array
    {
        return $this->groupId;
    }

    /**
     * @return int
     */
    public function getResult(): int
    {
        return $this->result;
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->entityId;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getBundle(): string
    {
        return $this->bundle;
    }

    public function __construct(private readonly int $entityId, private readonly int $categoryId, private readonly string $bundle)
    {
        if($this->bundle !== "Movies" && $this->bundle !== "Shows"){
            throw new \Exception("Bundle can be either Movies or Shows");
        }

        (new MysqlDynamicTables())->resolver(
            Database::database(),
            ["category_id", "category_name"],
            [
                "category_id"=>["int(11)", "auto_increment", "primary key"],
                "category_name"=>["varchar(250)", "not null"]
            ],
            "categories_entities",
            false
        );

        (new MysqlDynamicTables())->resolver(
            Database::database(),
            ["entity_id", "category_name", "bundle"],
            [
                "entity_id"=>["int(11)"],
                "category_name"=>["varchar(250)", "not null"],
                "bundle"=>["varchar(250)", "not null"]
            ],
            "entities_groups",
        );

        if($this->entityId === 0 || $this->categoryId === 0){

        }else {
            //get category name by cid
            $result = Selection::selectById("categories_entities", ["category_id" => $this->categoryId]);
            if (empty($result)) {
                throw new \Exception("Category by ID $this->categoryId not found");
            }
            $this->newGroup = [
                "entity_id" => $this->entityId,
                "category_name" => $result[0]["category_name"],
                "bundle" => $this->bundle
            ];
            $this->groupId = [
                "entity_id" => $this->entityId
            ];
        }
    }

    public function saveEntity(): TypeModerator
    {
        $r = Selection::selectById("entities_groups", $this->groupId);
        if(!empty($r)){
            $this->updateEntity();
        }else{
            $this->result = Insertion::insertRow("entities_groups",$this->newGroup);
        }
        return $this;
    }

    public function updateEntity(): TypeModerator
    {
        $this->result = Updating::update("entities_groups", $this->newGroup, $this->groupId);
        return $this;
    }

    public function deleteEntity(): TypeModerator
    {
        $this->result = Delete::delete("entities_groups",$this->groupId);
        return $this;
    }

    public static function categoriesHTML($id): string
    {
        $results = Selection::selectAll("categories_entities");
        $option = "";
        foreach ($results as $key=>$value){
            $option .= "<option value='{$value['category_id']}'>{$value['category_name']}</option>".PHP_EOL;
        }
        return "<select class='categories-class' id='category-$id' name='categories-name'>
                  <option value=''>Select Category</option>
                  $option
                </select>";
    }

    public static function findShowsWithoutEntity(): array
    {
        (new TypeModerator(0,0,"Shows"));
        $query = "SELECT * FROM entities_groups WHERE bundle = 'Shows'";
        $result1 = Query::query($query);

        $entityId = [];
        foreach ($result1 as $key=>$value){
            $entityId[] = $value['entity_id'];
        }

        $result2 = Selection::selectAll("tv_shows");
        $final = [];

        foreach ($result2 as $key=>$value){
            if(!in_array($value['show_id'], $entityId)){
               $final[] = $value;
            }
        }

        return $final;
    }

    public static function findMoviesWithoutEntity(): array
    {
        (new TypeModerator(0,0,"Movies"));
        $query = "SELECT * FROM entities_groups WHERE bundle = 'Movies'";
        $result1 = Query::query($query);

        $entityId = [];
        foreach ($result1 as $key=>$value){
            $entityId[] = $value['entity_id'];
        }

        $result2 = Selection::selectAll("movies");
        $final = [];

        foreach ($result2 as $key=>$value){
            if(!in_array($value['movie_id'], $entityId)){
                $final[] = $value;
            }
        }
        return $final;
    }

    public static function newCategoryName(string $name): bool
    {
        (new TypeModerator(0,0,"Movies"));
        return Insertion::insertRow("categories_entities", ["category_name"=>$name]);
    }
}
