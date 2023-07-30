<?php

namespace Modules\Search;

use Datainterface\mysql\TablesLayer;
use Datainterface\Query;
use Datainterface\Selection;
use Mpdf\Tag\S;
use Sessions\SessionManager;

class Search
{
    private string $searching;

    /**
     * @param string $searching
     */
    public function setSearching(string $searching): Search
    {
        $this->searching = $searching;
        return $this;
    }

    /**
     * @param string $type movie | tv show
     * @param string $search
     * @param bool $others
     * @return array title, id , image, release_date
     */
    public function search(string $type, string $search, bool $others = false): array
    {
        $this->searching = htmlspecialchars(strip_tags($search));
        $query = null;
        if($type === 'movie'){
            $query = "SELECT movie_id, title, release_date, movie_uuid FROM movies ".$this->buildSearchQuery($type, $others);
        }else{
           $query = "SELECT show_id, title, release_date, show_image, show_uuid FROM tv_shows ". $this->buildSearchQuery($type, $others);
        }

        return Query::query($query);
    }


    public function buildSearchQuery(string $type, $flag = false): string
    {
        $table = $type === 'movie' ? 'movies' : 'tv_shows';
        $queryLine =null;

        $schema = (new TablesLayer())->getSchemas()->schema();
        $thisTableSchema = $schema[$table];

        if($flag === false){
            if(empty(SessionManager::getSession($type)) || !isset($_SESSION['last-run']) || SessionManager::getSession('last-run') !== $this->searching){
                $fields = [];
                foreach ($thisTableSchema as $key=>$value){
                    $fields[] = $value['Field'] === 'created' ? null : $value['Field'];
                }
                $fields = array_filter($fields, 'strlen');
                $line = "";
                foreach ($fields as $key=>$value){
                    $line .= "$value LIKE '%$this->searching%' OR ";
                }
                $line ="WHERE ". trim(substr($line, 0, strlen($line) - 3));
                SessionManager::setSession($type, $line);
                SessionManager::setSession('last-run', $this->searching);
                return $line;
            }else{
                return SessionManager::getSession($type);
            }
        }else{
            return "WHERE title LIKE '%$this->searching%'";
        }
    }

    public function generalSearch($search): array
    {
        $result = $this->search('movie', $search, true);
        $result2 = $this->search('tv', $search, true);
        return $result + $result2;
    }
}