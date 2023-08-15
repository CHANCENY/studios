<?php

namespace Modules\Modals;

use Datainterface\Query;
use GlobalsFunctions\Globals;
use function functions\config;


class Catalogues
{
    public static function catalogueGrids(): array|false
    {

        $params = "m.movie_uuid AS uuid, m.title AS title, m.duration AS duration, m.release_date AS release_date, 
                  m.movie_image AS image, m.movie_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle";
        $query = "SELECT $params FROM movies AS m LEFT JOIN additional_information a ON
                   m.movie_id = a.internal_id WHERE a.bundle = 'movies' ORDER BY m.movie_changed DESC ".self::limit();

        $query2 = "SELECT $params FROM movies AS m LEFT JOIN additional_information a ON
                   m.movie_id = a.internal_id WHERE a.bundle = 'movies' ORDER BY m.movie_changed DESC";

        $data = Query::query($query);

        $d = Query::query($query2) ?? [];

        return ['data'=>$data, 'pager'=>self::buildPager(count($d), Globals::get('page'))];
    }

    private static function limit(): string
    {
        $page = Globals::get('page') ?? 1;
        $limit = config("PAGERLIMIT") ?? 6;
        $offset = (intval($page) - 1) * $limit;  // Calculate the offset

        if($offset < 0){
            return "LIMIT $limit";
        }else{
            return "LIMIT $limit OFFSET $offset";
        }
    }


    public static function buildPager(int $totalRows, int $page = 0): string
    {
        $limit = config("PAGERLIMIT") ?? 6;
        $total_pages = ceil($totalRows / intval($limit));

        $currentPage = Globals::url();

        $pager = " <li class='paginator__item paginator__item--prev'>
                      <a class='page-link' href='$currentPage?page=1' tabindex='-1' aria-disabled='true'>
                        <i class='icon ion-ios-arrow-back'></i>
                      </a>
                   </li>";
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pager .= "<li class='paginator__item paginator__item--active' aria-current='page'>
      				          <a class='page-link' href='$currentPage?page=$i'>$i</a>
   				           </li>";
            } else {
                if ($i == $page-3 && ($i != 1 || $i != 2 || $i != 3)) {
                    $pager .= '<li class="paginator__item" aria-current="page">
      				               <a class="page-link" disabled>...</a>
   				               </li>';
                }
                if ($i == $page-2 || $i == $page-1) {
                    $pager .= "<li class='paginator__item' aria-current='page'>
      				             <a class='page-link' href='$currentPage?page=$i'>$i</a>
   				               </li>";
                }
                if ($page < $total_pages && $i == $page+1 || $page < $total_pages && $i == $page+2) {
                    $pager .= "<li class='paginator__item' aria-current='page'>
      				              <a class='page-link' href='$currentPage?page=$i'>$i</a>
   				               </li>";
                }
                if ($page+2 < $total_pages && $i == $page+2) {
                    $pager .= "<li class='paginator__item active' aria-current='page'>
      				              <a class='page-link' href='#'>...</a>
   				               </li>";
                }
            }
        }

        $pager .= "<li class='paginator__item paginator__item--next'>
                     <a class='page-link' href='$currentPage?page=$total_pages' tabindex='-1' aria-disabled='true'>
                       <i class='icon ion-ios-arrow-forward'></i>
                     </a>
                   </li>";

        return $pager;
    }

    public static function catalogueLists(): array|false
    {
        $params = "m.movie_uuid AS uuid, m.title AS title, m.duration AS duration, m.release_date AS release_date, 
                  m.movie_image AS image, m.movie_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle";
        $query = "SELECT $params FROM movies AS m LEFT JOIN additional_information a ON
                   m.movie_id = a.internal_id WHERE a.bundle = 'movies' ORDER BY m.movie_changed DESC ".self::limit();

        $query2 = "SELECT $params FROM movies AS m LEFT JOIN additional_information a ON
                   m.movie_id = a.internal_id WHERE a.bundle = 'movies' ORDER BY m.movie_changed DESC";

        $data = Query::query($query);

        $d = Query::query($query2) ?? [];

        return ['data'=>$data, 'pager'=>self::buildPager(count($d), Globals::get('page'))];
    }


    public static function catalogueShowsGrid(): array|false
    {
        $params = "m.show_uuid AS uuid, m.title AS title, m.release_date AS release_date, 
                  m.show_image AS image, m.show_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle";
        $query = "SELECT $params FROM tv_shows AS m LEFT JOIN additional_information a ON
                   m.show_id = a.internal_id WHERE a.bundle = 'shows' ORDER BY m.show_changed DESC ".self::limit();

        $query2 = "SELECT $params FROM tv_shows AS m LEFT JOIN additional_information a ON
                   m.show_id = a.internal_id WHERE a.bundle = 'shows' ORDER BY m.show_changed DESC";

        $data = Query::query($query);

        $d = Query::query($query2) ?? [];

        return ['data'=>$data, 'pager'=>self::buildPager(count($d), Globals::get('page'))];
    }


    public static function catalogueShowsListing(): array|false
    {
        $params = "m.show_uuid AS uuid, m.title AS title, m.release_date AS release_date, 
                  m.show_image AS image, m.show_id AS id, m.description AS overview, a.popularity AS popularity, 
                  a.vote_average AS rating, a.vote_count AS vote, a.original_language AS lang, a.origin_country AS countries, 
                  a.genres AS genre, a.bundle AS bundle";
        $query = "SELECT $params FROM tv_shows AS m LEFT JOIN additional_information a ON
                   m.show_id = a.internal_id WHERE a.bundle = 'shows' ORDER BY m.show_changed DESC ".self::limit();

        $query2 = "SELECT $params FROM tv_shows AS m LEFT JOIN additional_information a ON
                   m.show_id = a.internal_id WHERE a.bundle = 'shows' ORDER BY m.show_changed DESC";

        $data = Query::query($query);

        $d = Query::query($query2) ?? [];

        return ['data'=>$data, 'pager'=>self::buildPager(count($d), Globals::get('page'))];
    }

}