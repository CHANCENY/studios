<?php

namespace Handlers;

use app\App;
use Datainterface\Database;
use Datainterface\MysqlDynamicTables;
use Datainterface\Query;
use Modules\Modals\Home;
use function functions\to_time_ago;

class DashBoard
{
   public function numbersTotal(App $myApp = new App()) :array
   {
       $totalMovies = 0;
       $totalShows = 0;
       $movies = Query::query("SELECT COUNT(movie_id) AS t FROM movies");
       $shows = Query::query("SELECT COUNT(show_id)  AS t FROM tv_shows");
       $seasons = Query::query("SELECT COUNT(season_id)  AS t FROM seasons");
       $episodes = Query::query("SELECT COUNT(episode_id)  AS t FROM episodes");
       return [
           'status'=>200,
           'shows'=>$shows[0]['t'] ?? 0,
           'movies'=>$movies[0]['t'] ?? 0,
           'seasons'=>$seasons[0]['t'] ?? 0,
           'episodes'=>$episodes[0]['t'] ?? 0,
       ];
   }

   public function twelveDaysUploads(App $myApp = new App()): array
   {
       $query = "SELECT * FROM movies WHERE created BETWEEN :start_date AND :end_date";
       $start_date = date('Y-m-d H:i:s', strtotime('-6 days'));
       $end_date = date('Y-m-d H:i:s');
       $data = Query::query($query, ['start_date'=>$start_date, 'end_date'=>$end_date]);
       $movies = [];
       if(!empty($data)){
           $dates = [];
           foreach ($data as $key=>$value){
               if(gettype($value) === 'array')
               {
                   $line = (new \DateTime($value['created']))->format('Y-m-d');
                   if(isset($dates[$line])){
                       $dates[$line] = intval($dates[$line]) + 1;
                   }else{
                       $dates[$line] = 1;
                   }
               }
           }
           krsort($dates);

           $movies = array_reverse($dates);
       }

       $query = "SELECT * FROM tv_shows WHERE created BETWEEN :start_date AND :end_date";
       $data = Query::query($query, ['start_date'=>$start_date, 'end_date'=>$end_date]);
       $shows = [];
       if(!empty($data)){
           $dates = [];
           foreach ($data as $key=>$value){
               if(gettype($value) === 'array')
               {
                   $line = (new \DateTime($value['created']))->format('Y-m-d');
                   if(isset($dates[$line])){
                       $dates[$line] = intval($dates[$line]) + 1;
                   }else{
                       $dates[$line] = 1;
                   }
               }
           }
           krsort($dates);
           $shows = array_reverse($dates);
       }
       return [
           'status'=>200,
           'movies'=>$movies,
           'shows'=>$shows
       ];
   }

   public function monthlyUploads(App $myApp = new App()): array
   {
       $moviesMonth = [
           "Jan"=>0,
           "Feb"=>0,
           "Mar"=>0,
           "Apr"=>0,
           "May"=>0,
           "Jun"=>0,
           "Jul"=>0,
           "Aug"=>0,
           "Sep"=>0,
           "Oct"=>0,
           "Nov"=>0,
           "Dec"=>0
       ];
       $showsMonth = [
           "Jan"=>0,
           "Feb"=>0,
           "Mar"=>0,
           "Apr"=>0,
           "May"=>0,
           "Jun"=>0,
           "Jul"=>0,
           "Aug"=>0,
           "Sep"=>0,
           "Oct"=>0,
           "Nov"=>0,
           "Dec"=>0
       ];
       // Get the current date
       $currentDate = date('Y');

       // Calculate the first day of the current month
       $firstDayOfMonth = date('Y-m-01 00:00:00', strtotime("$currentDate-01-01"));

       // Calculate the last day of the current month
       $lastDayOfMonth = date('Y-m-t 23:59:59', strtotime("$currentDate-12-31"));

       // SQL query with date range filtering
       $sql = "SELECT created, COUNT(show_id) as count
        FROM tv_shows 
        WHERE created BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth' 
        GROUP BY created";
       $shows = Query::query($sql);

       // SQL query with date range filtering
       $sql = "SELECT created, COUNT(movie_id) as count
        FROM movies 
        WHERE created BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth' 
        GROUP BY created";
       $movies = Query::query($sql);

       $allShows = [];
       foreach ($shows as $key=>$value)
       {
           if(gettype($value) === "array")
           {
               $date = (new \DateTime( $value['created']))->format("M");
               if(isset($showsMonth[$date]))
               {
                   $showsMonth[$date] += intval($value['count']);
               }
           }
       }

       $allMovies = [];
       foreach ($movies as $key=>$value)
       {
           if(gettype($value) === "array")
           {
               $date = (new \DateTime( $value['created']))->format("M");
               if(isset($moviesMonth[$date]))
               {
                   $moviesMonth[$date] += intval($value['count']);
               }
           }
       }
       return [
           'status'=>200,
           'movies'=>$moviesMonth,
           'shows'=>$showsMonth
       ];
   }

   public function upComingListing(App $myApp = new App()): array
   {
       $movies = Home::newPremierMovies();
       return [
           'status'=>200,
           'results'=>$movies
       ];
   }

   public function usersListing(App $myapp = new App()): array
   {
       $attr = [
           "country"=>['varchar(100)'],
           "state"=>['varchar(100)'],
           "zip"=>['int(11)'],
           "gender"=>['varchar(100)'],
           'birthday'=>['varchar(100)'],
           'uid'=>['int(11)']
       ];

       (new MysqlDynamicTables())->resolver(
           Database::database(),
           array_keys($attr),
           $attr,
           "users_additional"
       );
       $page = $myapp->getParamsData();
       // SQL query
       $sql = "SELECT
            uu.firstname,
            uu.lastname,
            uu.mail,
            uu.phone,
            uu.address,
            uu.created,
            uu.verified,
            uu.blocked,
            uu.role,
            uu.uid,
            uu.image,
            COALESCE(addit.country, '') AS country,
            COALESCE(addit.state, '') AS state,
            COALESCE(addit.zip, '') AS zip,
            COALESCE(addit.gender, '') AS gender,
            COALESCE(addit.birthday, '') AS birthday
        FROM
            users AS uu
        LEFT JOIN
            users_additional AS addit
        ON
            addit.uid = uu.uid";

       if(empty($page['page']))
       {
           $page['page'] = 0;
       }

       $d = Query::query($sql);
       $chunked = array_chunk($d, 12);
       $list = [];
       if(isset($chunked[$page['page']]))
       {
           $list = $chunked[$page['page']];
       }
       return [
           'status'=>200,
           'results'=> empty($list) ? [] : array_values($list)
       ];
   }

   public function newUsersListing(App $myapp = new App()): array
   {
       $query = "SELECT firstname, lastname, mail, phone, address, created, verified, blocked, role, uid, image FROM users WHERE created BETWEEN :start_date AND :end_date";
       $start_date = date('Y-m-d H:i:s', strtotime('-15 days'));
       $end_date = date('Y-m-d H:i:s');
       return [
           'status'=>200,
           'results'=>array_values(Query::query($query,['start_date'=>$start_date, 'end_date'=>$end_date]))
       ];
   }

   public function managementListing(App $myApp = new App()): array
   {
       $data = $this->numbersTotal();
       $monthly = $this->monthlyUploads();

       $showsMonthly = $monthly['shows'] ?? [];
       $moviesMonthly = $monthly['movies'] ?? [];

       $totalThisMonth = 1;
       $totalThisMonth2 = 1;

       foreach ($moviesMonthly as $key=>$value) {
           if (gettype($value) === "array") {
               $d = (new \DateTime($key))->format('m');
               $dm = (new \DateTime('now'))->format('m');

               if ($dm === $d) {
                   $totalThisMonth += intval($value);
               }
           }
       }

       foreach ($showsMonthly as $key=>$value)
       {
           if(gettype($value) === "array")
           {
               $d = (new \DateTime($key))->format('m');
               $dm = (new \DateTime('now'))->format('m');

               if($dm === $d)
               {
                   $totalThisMonth2 += intval($value);
               }
           }
       }

       return [
           'shows'=>  number_format(($totalThisMonth2 / intval($data['shows'])) * 100, 2),
           'movies'=> number_format(($totalThisMonth / intval($data['movies'])) * 100, 2),
       ];
   }

   public function alertsListing(App $myApp = new App()): array
   {
       $sql = "SELECT event_name, event_id, created FROM event_news WHERE event_sent_status = 0";
       $data = Query::query($sql);

       $processed = [];
       foreach ($data as $key=>$value)
       {
           if(gettype($value) === 'array')
           {
               $value['time'] = to_time_ago($value['created']);
               $value['title'] = $value['event_name'];
               $value['id'] = $value['event_id'];
               unset($value['event_id']);
               unset($value['event_name']);
               unset($value['created']);
               $processed[] = $value;
           }
       }
       return [
           'status'=>200,
           'results'=>array_reverse(array_values($processed))
       ];
   }
}