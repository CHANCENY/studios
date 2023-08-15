<?php

namespace Modules\Modals;

use Datainterface\Selection;
use MongoDB\Driver\Query;

class Filters
{
    public function __construct(
        private readonly string $title = "",
        private readonly string $genre = "",
        private readonly string $rating = "",
        private readonly string $release_years = "",
    )
    {
    }

    public function basicSearch(): array
    {
        $query = "SELECT 
    m.show_uuid AS uuid, 
    m.title AS title, 
    m.release_date AS release_date, 
    m.show_image AS image, 
    m.show_id AS id, 
    m.description AS overview, 
    a.popularity AS popularity, 
    a.vote_average AS rating, 
    a.vote_count AS vote, 
    a.original_language AS lang, 
    a.origin_country AS countries, 
    a.genres AS genre, 
    a.bundle AS bundle 
FROM 
    tv_shows AS m 
LEFT JOIN 
    additional_information a ON m.show_id = a.internal_id 
WHERE 
    a.bundle = 'movies' 
    AND m.title LIKE '%$this->title%'
ORDER BY 
    m.show_changed DESC";

        $query1 = "SELECT 
    m.movie_uuid AS uuid, 
    m.title AS title, 
    m.release_date AS release_date, 
    m.movie_image AS image, 
    m.movie_id AS id, 
    m.description AS overview, 
    a.popularity AS popularity, 
    a.vote_average AS rating, 
    a.vote_count AS vote, 
    a.original_language AS lang, 
    a.origin_country AS countries, 
    a.genres AS genre, 
    a.bundle AS bundle 
FROM 
    movies AS m 
LEFT JOIN 
    additional_information a ON m.movie_id = a.internal_id 
WHERE 
    a.bundle = 'movies' 
    AND m.title LIKE '%$this->title%'
ORDER BY 
    m.movie_changed DESC";

        return array_merge(\Datainterface\Query::query($query) ?? [], \Datainterface\Query::query($query1) ?? []);
    }

    public function genres(): array
    {
        $data = Selection::selectAll("additional_information");
        $genres = [];
        $i = 0;
        foreach ($data as $key=>$value){
            $list = explode('|', $value['genres']);
            if($i === 0){
                $genres = $list;
                $i = 56;
            }else{
                $v = $this->unique($genres, $list);
                if(isset($value['genres']) && !empty($v)){
                    $genres = array_merge($genres, $v);
                }
            }

        }

        return $genres;
    }

    private function unique(&$old, $new): array
    {
        $returns = [];
        foreach ($new as $key=>$value){
            if(!array_search(trim($value), $old)){
                $returns[] = trim($value);
            }
        }
        return $returns;
    }

    public function advanceSearch(): array
    {
        $ratingList = explode('-', $this->rating);
        $years = explode('-', $this->release_years);
        $fdate = $years[0]."01-01";
        $edate = $years[1]."12-30";

        $rfirst = floatval($ratingList[0]);
        $rend = floatval($ratingList[1]);

        $query = "SELECT 
    m.show_uuid AS uuid, 
    m.title AS title, 
    m.release_date AS release_date, 
    m.show_image AS image, 
    m.show_id AS id, 
    m.description AS overview, 
    a.popularity AS popularity, 
    a.vote_average AS rating, 
    a.vote_count AS vote, 
    a.original_language AS lang, 
    a.origin_country AS countries, 
    a.genres AS genre, 
    a.bundle AS bundle 
FROM 
    tv_shows AS m 
LEFT JOIN 
    additional_information a ON m.show_id = a.internal_id 
WHERE 
    a.bundle = 'movies' 
    AND (a.genres LIKE '%$this->genre%')
    AND (a.vote_average BETWEEN $rfirst AND $rend)
    AND (m.release_date BETWEEN '$fdate' AND '$edate')
ORDER BY 
    m.show_changed DESC";


        $query1 = "SELECT 
    m.movie_uuid AS uuid, 
    m.title AS title, 
    m.release_date AS release_date, 
    m.movie_image AS image, 
    m.movie_id AS id, 
    m.description AS overview, 
    a.popularity AS popularity, 
    a.vote_average AS rating, 
    a.vote_count AS vote, 
    a.original_language AS lang, 
    a.origin_country AS countries, 
    a.genres AS genre, 
    a.bundle AS bundle 
FROM 
    movies AS m 
LEFT JOIN 
    additional_information a ON m.movie_id = a.internal_id 
WHERE 
    a.bundle = 'movies' 
    AND (a.genres LIKE '%$this->genre%')
    AND (a.vote_average BETWEEN $rfirst AND $rend)
    AND (m.release_date BETWEEN '$fdate' AND '$edate')
ORDER BY 
    m.movie_changed DESC";

        return array_merge(\Datainterface\Query::query($query) ?? [], \Datainterface\Query::query($query1) ?? []);
    }

}