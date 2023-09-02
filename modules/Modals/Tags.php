<?php

namespace Modules\Modals;

use Datainterface\Query;
use Datainterface\Tables;
use InvalidArgumentException;
use Modules\CountriesModular;

/**
 *
 */
class Tags
{
    /**
     * valid tags names list
     */
    const TAGNAMES = ['countries', 'genres', 'votes', 'rates', 'popularity','languages'];

    /**
     * corresponding columns in additional table
     */
    const TAGMAPPERS = [
         'countries'=>'origin_country',
         'languages'=>'original_language',
         'votes'=>'vote_count',
         'rates'=>'vote_average',
         'popularity'=>'popularity',
         'genres'=>'genres'
     ];
    /**
     * @var array|false
     */
    private array|false $unproccessed;
    /**
     * @var array
     */
    private array $tags;

    /**
     * @param string $tagName need to be vaild tagname eg countries
     */
    public function __construct(private readonly string $tagName)
    {
        if(!in_array($this->tagName, Tags::TAGNAMES)){
            throw new InvalidArgumentException("Invalid tag name passed to ".get_class($this));
        }
        $this->loadTags();
        $this->process();
    }

    /**
     * @return void
     */
    private function loadTags(): void
    {
       $column = Tags::TAGMAPPERS[$this->tagName];
       $query = "SELECT $column, bundle FROM additional_information";
       $this->unproccessed = Query::query($query);
    }

    /**
     * @return void
     */
    private function process(): void
    {
      if($this->tagName === 'countries'){
          $this->tags = $this->countries();
      }
      if($this->tagName === 'genres'){
          $this->tags = $this->genres();
      }
      if($this->tagName === 'languages'){
          $this->tags = $this->languages();
      }
      if($this->tagName === 'rates'){
          $this->tags = $this->rates();
      }
      if($this->tagName === 'votes'){
          $this->tags = $this->votes();
      }
      if($this->tagName === 'popularity'){
          $this->tags = $this->popularity();
      }
    }

    /**
     * @return array
     */
    private function countries(): array
    {
        $tags = [];
        foreach ($this->unproccessed as $key=>$value){
            if(isset($value['origin_country'])){
                $countryCodes = $value['origin_country'];
                $countryCodes = str_contains($countryCodes, ',') ? explode(',', $countryCodes) : $countryCodes;
                if(gettype($countryCodes) === 'array'){
                    for ($i = 0; $i < count($countryCodes); $i++){
                        if(!empty($countryCodes[$i])){
                            $country = CountriesModular::getCountryName(trim($countryCodes[$i]));
                            if(!empty($country)){
                                $tags[$value['bundle']][trim($countryCodes[$i])] = $country;
                            }
                        }
                    }
                }else{
                    if(!empty($countryCodes)){
                        $country = CountriesModular::getCountryName(trim($countryCodes));
                        if(!empty($country)){
                            $tags[$value['bundle']][trim($countryCodes)] = $country;
                        }
                    }
                }
            }
        }
        return $tags;
    }

    /**
     * @return array
     */
    private function genres(): array
    {
        $tags = [];
        foreach ($this->unproccessed as $key=>$value){
            if(isset($value['genres'])){
                $genres = $value['genres'];
                $list = str_contains($genres, '|') ? explode('|', $genres) : $genres;
                $list1 = str_contains($genres, ',') ? explode(',', $genres) : $genres;
                if(gettype($list) === 'array'){
                    $genres = $list;
                }
                if(gettype($list1) === 'array'){
                    $genres = $list1;
                }
                if(gettype($genres) === 'array'){
                    for ($i = 0; $i < count($genres); $i++){
                        if(!empty($genres[$i])){
                            $tags[$value['bundle']][trim($genres[$i])] = $genres[$i];
                        }
                    }
                }else{
                    if(!empty($genres)){
                        $tags[$value['bundle']][trim($genres)] = $genres;
                    }
                }
            }
        }
        return $tags;
    }

    /**
     * @return array
     */
    private function languages(): array
    {
        $tags = [];
        foreach ($this->unproccessed as $key=>$value){
            if(isset($value['original_language'])){
                $languages = $value['original_language'];
                if(!empty($languages)){
                    $tags[$value['bundle']][trim($languages)] = $languages;
                }
            }
        }
        return $tags;
    }

    /**
     * @return array
     */
    private function rates(): array
    {
        $tags = [];
        foreach ($this->unproccessed as $key=>$value){
            if(isset($value['vote_average'])){
                $ratings = $value['vote_average'];
                if(!empty($ratings)){
                    $tags[$value['bundle']][intval(trim($ratings))] = $ratings;
                }
            }
        }
        return $tags;
    }

    /**
     * @return array
     */
    private function votes(): array
    {
        $tags = [];
        foreach ($this->unproccessed as $key=>$value){
            if(isset($value['vote_count'])){
                $votes = $value['vote_count'];
                if(!empty($votes)){
                    $tags[$value['bundle']][intval(trim($votes))] = $votes;
                }
            }
        }
        return $tags;
    }

    /**
     * @return array
     */
    public function popularity(): array
    {
        $tags = [];
        foreach ($this->unproccessed as $key=>$value){
            if(isset($value['popularity'])){
                $popularity = $value['popularity'];
                if(!empty($popularity)){
                    $tags[$value['bundle']][intval(trim($popularity))] = $popularity;
                }
            }
        }
        return $tags;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

}