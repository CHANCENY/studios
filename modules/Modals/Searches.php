<?php

namespace Modules\Modals;

use Datainterface\Query;

class Searches
{

    public function __construct(private readonly string $genre, private readonly string $bundle)
    {
    }

    public function searchByGenres(): array|false
    {

        $query = "";
        if($this->bundle === "shows"){
            $params = "m.show_uuid AS uuid, m.title AS title, m.release_date AS release_date, 
                  m.show_image AS image, m.show_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle";
            $query = "SELECT $params FROM tv_shows AS m LEFT JOIN additional_information a ON
                   m.show_id = a.internal_id WHERE a.bundle = 'shows' AND a.genres LIKE '%$this->genre%' ORDER BY m.show_changed DESC ";

        }

        if($this->bundle === 'movies'){
            $params = "m.movie_uuid AS uuid, m.title AS title, m.duration AS duration, m.release_date AS release_date, 
                  m.movie_image AS image, m.movie_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle";
            $query = $query= "SELECT $params FROM movies AS m LEFT JOIN additional_information a ON
                   m.movie_id = a.internal_id WHERE a.bundle = 'movies' AND a.genres LIKE '%$this->genre%' ORDER BY m.movie_changed DESC ";

        }

        return Query::query($query);
    }


    public function searchByCountry(): array|false
    {
        $query = "";
        if($this->bundle === "shows"){
            $params = "m.show_uuid AS uuid, m.title AS title, m.release_date AS release_date, 
                  m.show_image AS image, m.show_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle";
            $query = "SELECT $params FROM tv_shows AS m LEFT JOIN additional_information a ON
                   m.show_id = a.internal_id WHERE a.bundle = 'shows' AND a.origin_country LIKE '%$this->genre%' ORDER BY m.show_changed DESC ";

        }

        if($this->bundle === 'movies'){
            $params = "m.movie_uuid AS uuid, m.title AS title, m.duration AS duration, m.release_date AS release_date, 
                  m.movie_image AS image, m.movie_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle";
            $query = $query= "SELECT $params FROM movies AS m LEFT JOIN additional_information a ON
                   m.movie_id = a.internal_id WHERE a.bundle = 'movies' AND a.origin_country LIKE '%$this->genre%' ORDER BY m.movie_changed DESC ";

        }

        return Query::query($query);
    }


}