<?php

namespace Modules\Renders;

use Datainterface\Database;
use Datainterface\Delete;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Selection;
use GlobalsFunctions\Globals;
use Json\Json;
use Sessions\SessionManager;

/**
 * SEOTags handles meta tags creation appending and processing
 */
class SEOTags
{
    private mixed $dataValues;

    public function __construct(private readonly string $token)
   {
   }

   public function set(): void
   {
       if(!isset($this->token)){
           throw new \Exception("Token not initialized");
       }

       $this->dataValues = serialize($this->dataValues);
       $already = Selection::selectById('seo_tags_data_collection', ['token'=>$this->token]);
       if(!empty($already)){
           $this->clear();
       }
       Insertion::insertRow('seo_tags_data_collection', ['data'=>$this->dataValues]);
   }

    /**
     * @return mixed you can get data as it was saved before calling @function process otherwise you will
     * get string
     */
   public function get(): mixed
   {
       if(!isset($this->token)){
           throw new \Exception("Token not initialized");
       }

       $r = Selection::selectById('seo_tags_data_collection', ['token'=>$this->token]);
       return unserialize($r[0]['data']);
   }

   public function clear(): bool
   {
       if(!isset($this->token)){
           throw new \Exception("Token not initialized");
       }
       return Delete::delete('seo_tags_data_collection',['token'=>$this->token]);
   }

    /**
     * @param mixed $dataValues this values Assoc array eg ['tag name'=> 'tag content' ] or can be a string
     *  eg tagname::tacontent~tagname::tacontent
     *
     * @return SEOTags
     */
   public function data(mixed $dataValues): SEOTags
   {
       $this->dataValues = $dataValues;
       return $this;
   }

    /**
     * @return $this after converting meta tag data to meta tags for actual html use get to retrieve the values
     */
   public function process(): SEOTags
   {
       $data = $this->get();
       $metaTags = "";
       if(gettype($data) === 'array')
       {
           foreach ($data as $tagName=>$tagContent)
           {
               $metaTags .= "<meta name='$tagName' content='$tagContent'>".PHP_EOL;
           }
       }
       elseif (gettype($data) === 'string')
       {
          $data = str_contains($data, '~') ? explode('~', $data) : $data;

          if(gettype($data) === 'array'){
              foreach ($data as $key=>$tagString)
              {
                  $list = explode('::', $tagString);
                  $metaTags .= "<meta name='{$list[0]}' content='{$list[1]}'>".PHP_EOL;
              }
          }else{
              $list = explode('::', $data);
              $metaTags .= "<meta name='{$list[0]}' content='{$list[1]}'>".PHP_EOL;
          }
       }
       SessionManager::setSession($this->token, $metaTags);
       return $this;
   }


    /**
     * @return string token create or old token
     */
   public static function getToken(): string
   {
       $currentURL = Globals::uri();
       $currentTitle = Globals::viewTitleOnRequest();
       $token = Json::uuid();
       $identity = str_replace(' ','-', $currentTitle).'-'
           .str_replace(' ','-', $currentURL);

       (new MysqlDynamicTables())->resolver(
           Database::database(),
           ['identity', 'token'],
           ['identity'=>['varchar(250)', 'not null'], 'token'=>['varchar(250)', 'not null']],
           'seo_tokens'
       );

       $already = Selection::selectById('seo_tokens',['identity'=>$identity]);
       if(!empty($already)){
           return $already[0]['token'];
       }else{
           Insertion::insertRow('seo_tokens',['identity'=>$identity, 'token'=>$token]);
       }
       return $token;
   }

}