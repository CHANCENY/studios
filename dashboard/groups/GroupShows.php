<?php

namespace groups;

use Datainterface\Query;

class GroupShows
{
    /**
     * @var array|mixed
     */
    private mixed $show;

    public function __call(string $name, array $arguments)
    {
        return match ($name) {
            'title' => $this->show['title'] ?? null,
            'date' => $this->show['release_date'] ?? (new \DateTime("now"))->format("d/m/y"),
            'overview' => $this->show['description'] ?? null,
            'image' => $this->show['show_image'] ?? null,
            default => $this->show,
        };
    }

    public function showsListings(): array
    {
        $query = "SELECT tv.show_id AS id, tv.title,  tv.release_date AS date,  tv.show_image AS image, CASE  WHEN ai.additional_id IS NOT NULL THEN 1  ELSE 0 END AS active FROM tv_shows AS tv
         LEFT JOIN additional_information AS ai ON tv.show_id = ai.internal_id WHERE ai.bundle = 'shows' OR ai.bundle IS NULL ORDER BY tv.created DESC";

        $data = Query::query($query);
        if(!empty($data))
        {
            return array_values($data);
        }
        return array();
    }

    public function searchByName(string $name): array
    {

        $query = "SELECT
    tv.show_id AS id,
    tv.title,
    tv.release_date AS date,
    tv.show_image AS image,
    CASE
        WHEN ai.additional_id IS NOT NULL THEN 1
        ELSE 0
    END AS active
FROM
    tv_shows AS tv
LEFT JOIN
    additional_information AS ai
ON
    tv.show_id = ai.internal_id
WHERE
    (ai.bundle = 'shows' OR ai.bundle IS NULL)
    AND tv.title LIKE '%$name%'
ORDER BY
    tv.created DESC;
";

        $data = Query::query($query);
        if(!empty($data))
        {
            return array_values($data);
        }
        return array();
    }

    public function searchByID(int $id): array
    {

        $query = "SELECT
    tv.show_id AS id,
    tv.title,
    tv.release_date AS date,
    tv.show_image AS image,
    CASE
        WHEN ai.additional_id IS NOT NULL THEN 1
        ELSE 0
    END AS active
FROM
    tv_shows AS tv
LEFT JOIN
    additional_information AS ai
ON
    tv.show_id = ai.internal_id
WHERE
    (ai.bundle = 'shows' OR ai.bundle IS NULL)
    AND tv.show_id = :id
ORDER BY
    tv.created DESC;
";

        $data = Query::query($query, ['id'=>$id]);
        if(!empty($data))
        {
            return array_values($data);
        }
        return array();
    }

    public function searchBYNameAndID(string $name, int $id): array{

       $query = "SELECT
    tv.show_id AS id,
    tv.title,
    tv.release_date AS date,
    tv.show_image AS image,
    CASE
        WHEN ai.additional_id IS NOT NULL THEN 1
        ELSE 0
    END AS active
FROM
    tv_shows AS tv
LEFT JOIN
    additional_information AS ai
ON
    tv.show_id = ai.internal_id
WHERE
    (ai.bundle = 'shows' OR ai.bundle IS NULL)
    AND tv.show_id = :id
    AND tv.title = :title
ORDER BY
    tv.created DESC;
";
        $data = Query::query($query, ['id'=>$id, 'title'=>$name]);
        if(!empty($data))
        {
            return array_values($data);
        }
        return array();
    }

    public function loadForEdit(int $id): void
    {
        $data = Query::query("SELECT * FROM tv_shows WHERE show_id = :id", ['id'=>$id]);
        if(!empty($data))
        {
            $this->show = $data[0];
        }
        else{
            $this->show = [];
        }
    }
}
