<?php

namespace groups;

use Datainterface\Query;

class GroupShows
{
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
}
