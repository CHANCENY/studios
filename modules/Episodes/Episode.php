<?php

namespace Modules\Episodes;

use Datainterface\Query;
use Datainterface\Selection;

class Episode
{

    public function episodes(): array
    {
        $query = "SELECT * FROM episodes WHERE season_id IS NOT NULL AND url IS NOT NULL ORDER BY episode_id DESC";
        return Query::query($query);
    }

    public function getEpisodeShowTitle($season_id):string|null
    {
        $query = "SELECT tv.title FROM seasons AS se LEFT JOIN tv_shows AS tv ON tv.show_id = se.show_id WHERE se.season_id = :id";
        $result = Query::query($query, ['id'=>$season_id]);
        return $result[0]['title'] ?? null;
    }

    public function getEpisodesUnfilter(): array
    {
        return Selection::selectAll("episodes");
    }
}