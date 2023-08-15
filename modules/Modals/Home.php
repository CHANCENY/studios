<?php

namespace Modules\Modals;

use Datainterface\Query;
use GlobalsFunctions\Globals;


class Home
{
   public static function newReleaseMovies(): array
   {
       /**
        * Will session result check first to return
        */
//       if(isset($_SESSION['new_release_movies'])){
//           return $_SESSION['new_release_movies'];
//       }

       $params = "m.title AS title, m.movie_image AS image, m.description AS overview, 
       a.vote_average AS rating, m.movie_uuid AS uuid, a.genres AS genre, a.bundle AS bundle";

       $query = "SELECT $params FROM movies AS m LEFT JOIN additional_information AS a ON m.movie_id = a.internal_id 
         WHERE a.bundle = 'movies' ORDER BY m.release_date DESC LIMIT 3";
       $data = Query::query($query);

       $_SESSION['new_release_movies'] = $data;
       return $data;
   }

    public static function newReleaseShows(): array
    {
        /**
         * Will session result check first to return
         */
//        if(isset($_SESSION['new_release_shows'])){
//            return $_SESSION['new_release_shows'];
//        }

        $params = "m.title AS title, m.show_image AS image, m.description AS overview, 
       a.vote_average AS rating, m.show_uuid AS uuid, a.genres AS genre, a.bundle AS bundle";

        $query = "SELECT $params FROM tv_shows AS m LEFT JOIN additional_information AS a ON m.show_id = a.internal_id 
          WHERE a.bundle = 'shows' ORDER BY m.release_date DESC LIMIT 3";
        $data = Query::query($query);

        $_SESSION['new_release_shows'] = $data;
        return $data;
    }

    public static function moviesHighVotes(): array
    {
        /**
         * Will session result check first to return
         */
//        if(isset($_SESSION['new_release_movies_high'])){
//            return $_SESSION['new_release_movies_high'];
//        }

        $params = "m.title AS title, m.movie_image AS image, m.description AS overview, 
       a.vote_average AS rating, m.movie_uuid AS uuid, a.genres AS genre, a.bundle AS bundle";

        $query = "SELECT $params FROM movies AS m LEFT JOIN additional_information AS a ON m.movie_id = a.internal_id 
         WHERE a.bundle = 'movies' ORDER BY a.vote_average DESC LIMIT 12";
        $data = Query::query($query);

        $_SESSION['new_release_movies_high'] = $data;
        return $data;
    }

    public static function showsHighVotes(): array
    {
        /**
         * Will session result check first to return
         */
//        if(isset($_SESSION['new_release_shows_high'])){
//            return $_SESSION['new_release_shows_high'];
//        }

        $params = "m.title AS title, m.show_image AS image, m.description AS overview, 
       a.vote_average AS rating, m.show_uuid AS uuid, a.genres AS genre, a.bundle AS bundle";

        $query = "SELECT $params FROM tv_shows AS m LEFT JOIN additional_information AS a ON m.show_id = a.internal_id 
         WHERE a.bundle = 'shows' ORDER BY a.vote_average DESC LIMIT 12";
        $data = Query::query($query);

        $_SESSION['new_release_shows_high'] = $data;
        return $data;
    }

    public static function newThisSeasonRandomised(): array
    {
        /**
         * Will session result check first to return
         */
//        if(isset($_SESSION['new_release_new_this_season'])){
//            return $_SESSION['new_release_new_this_season'];
//        }

        $params = "m.title AS title, m.show_image AS image, m.description AS overview, 
       a.vote_average AS rating, m.show_uuid AS uuid, a.genres AS genre, a.bundle AS bundle";

        $query = "SELECT $params FROM tv_shows AS m LEFT JOIN additional_information AS a ON m.show_id = a.internal_id 
          WHERE a.bundle = 'shows' ORDER BY m.release_date DESC, a.vote_average DESC LIMIT 10";
        $data = Query::query($query);


        $params = "m.title AS title, m.movie_image AS image, m.description AS overview, 
       a.vote_average AS rating, m.movie_uuid AS uuid, a.genres AS genre, a.bundle AS bundle";

        $query = "SELECT $params FROM movies AS m LEFT JOIN additional_information AS a ON m.movie_id = a.internal_id 
          WHERE a.bundle = 'movies' ORDER BY m.release_date DESC, a.vote_average DESC LIMIT 10";
        $data1 = Query::query($query);

        $combine = array_merge($data, $data1);
        $_SESSION['new_release_new_this_season'] = $combine;

        shuffle($combine);
        return $combine;
    }

    public static function newPremierMovies(): array
    {
        /**
         * Will session result check first to return
         */
//        if(isset($_SESSION['new_expected_pre'])){
//            return $_SESSION['new_expected_pre'];
//        }

        $authToken = \functions\config('TMDB');

        $movies = [];

        for($i = 1; $i < 5; $i++){
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.themoviedb.org/3/movie/upcoming?language=en-US&page=$i",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: $authToken",
                    "accept: application/json"
                ],
            ]);

            $movies1 = json_decode(curl_exec($curl),true);
            $err = curl_error($curl);

            if(empty($err)){
                $movies = array_merge($movies, $movies1['results']);
            }
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/genre/movie/list?language=en",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: $authToken",
                "accept: application/json"
            ],
        ]);

        $genresdata = json_decode(curl_exec($curl), true);
        $err = curl_error($curl);
        curl_close($curl);

        $genres = [];
        if(empty($err)){
            $genres = $genresdata['genres'];
        }

        foreach ($movies as $key=>$value){

            $currentDate = date('Y-m-d',time());
            $releaseDate = strtotime($value['release_date']);
            if($releaseDate < $currentDate){
                $genre_ids = $value['genre_ids'] ?? [];
                unset($value['genre_ids']);
                $value['genre'] = self::genresFetch($genre_ids, $genres);
                $returnData[] = $value;
            }
        }

        $_SESSION['new_expected_pre'] = $returnData;
        return $returnData;
    }

    public static function genresFetch($movieGenres, $all): string
    {
        $list = [];
        foreach ($all as $key=>$value){
            if(in_array($value['id'], $movieGenres)){
               $list[] = $value['name'];
            }
        }
        return implode(' | ', $list);
    }

    public static function clearHomeSession()
    {
        if(isset($_SESSION['new_release_shows'])){
            unset($_SESSION['new_release_shows']);
        }
        if(isset($_SESSION['new_release_movies'])){
            unset($_SESSION['new_release_movies']);
        }
        if(isset($_SESSION['new_release_movies_high'])){
            unset($_SESSION['new_release_movies_high']);
        }
        if(isset($_SESSION['new_release_shows_high'])){
            unset($_SESSION['new_release_shows_high']);
        }
        if(isset($_SESSION['new_release_new_this_season'])){
            unset($_SESSION['new_release_new_this_season']);
        }
        if(isset($_SESSION['new_expected_pre'])){
            unset($_SESSION['new_expected_pre']);
        }
    }

    /**
     * @param string $genre action|comedy
     * @return array
     */
    public static function buildGenre(string $genre, string $bundle = "movies"): array
    {
        $genres = [];
       if(!empty($genre)){
           $list = explode('|', $genre);
           $i = 0;
           foreach ($list as $key=>$value){

               if($i <= 1){
                   $genres[] = [
                       "link"=>Globals::protocal()."://". Globals::serverHost()."/genres?genre=".urlencode(trim($value))."&type=$bundle",
                       "text"=>ucfirst(trim($value)),
                       "title"=>ucfirst(trim($value)),
                       "alt"=>ucfirst(trim($value)),
                       "rel"=>ucfirst(trim($value)),
                   ];
               }
               $i++;
           }
       }
       return $genres;
    }

    /**
     * @param $type movies or shows
     * @param $uuid moive_uuid or show_uuid
     * @return string
     */
    public static function buildLinkFor($type, $uuid): string
    {
       if($type === "movies"){
           return Globals::protocal()."://". Globals::serverHost()."/film-overview-details?movie-id=".$uuid;
       }
       return Globals::protocal()."://". Globals::serverHost(). "/series-overview-details?series-id=".$uuid;
    }


    public static function buildCountryLink(string $countries, $bundle = "movies"): array
    {
        $country = [];
        if(!empty($countries)){
            $list = explode(',', $countries);
            $i = 0;
            foreach ($list as $key=>$value){

                if($i <= 1){
                    $country[] = [
                        "link"=>Globals::protocal()."://". Globals::serverHost()."/countries?country=".urlencode(trim($value))."&type=$bundle",
                        "text"=>ucfirst(trim($value)),
                        "title"=>ucfirst(trim($value)),
                        "alt"=>ucfirst(trim($value)),
                        "rel"=>ucfirst(trim($value)),
                    ];
                }
                $i++;
            }
        }
        return $country;
    }


}