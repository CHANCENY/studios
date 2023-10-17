<?php

namespace groups;

use Datainterface\Query;


class GroupOverview
{
    public function __call(string $name, array $arguments)
    {
        return match ($name) {
            'movie' => $this->details("movies", $arguments[0], "movie_id")[0] ?? [],
            'show' => $this->details("tv_shows",$arguments[0], "show_id")[0] ?? [],
            'season' => $this->details("seasons",$arguments[0],"season_id")[0] ?? [],
            'episode'=>$this->details("episodes", $arguments[0], 'episode_id')[0] ?? [],
            default => null,
        };
    }

    private function details($table, $id, $column): array
    {
        return Query::query("SELECT * FROM $table WHERE $column = :id", ['id'=>$id]);
    }
}