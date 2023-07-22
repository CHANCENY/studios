<?php

namespace Modules\Renders;

use Core\Router;
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
       (new MysqlDynamicTables())->resolver(
         Database::database(),
         ["token", "data"],
           [
               "token"=>["varchar(250)", "not null"],
               "data"=>["longblob"]
           ],
           "seo_tags_data_collection"
       );
   }

   public function set(): void
   {
       if(!isset($this->token)){
           throw new \Exception("Token not initialized");
       }

       $this->dataValues = serialize($this->dataValues);
       $already = Selection::selectById('seo_tags_data_collection',
           ['token'=>$this->token]
       );

       if(!empty($already)){
           $this->clear();
       }
       Insertion::insertRow('seo_tags_data_collection',
           [
               'data'=>$this->dataValues,
               'token'=>$this->token
           ]
       );
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
       if(empty($r)){
           return [];
       }
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
               if(str_contains($tagName, "og")){
                   $metaTags .= "<meta property='$tagName' content='$tagContent' />".PHP_EOL;
               }else{
                   $metaTags .= "<meta name='$tagName' content='$tagContent'>".PHP_EOL;
               }
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
       $this->dataValues = $metaTags;
       return $this;
   }


    /**
     * @return string token create or old token
     */
   public static function getToken($uniqueToken = null): string
   {
       $currentURL = Globals::uri();
       $currentTitle = Globals::viewTitleOnRequest();
       $token = $uniqueToken ?? Json::uuid();

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

   public static function token(): string
   {
       return Globals::protocal()."://".Globals::serverHost().Globals::uri();
   }

   public function seo(): string
   {
       return $this->dataValues;
   }

    /**
     * @param array $data ASSOC array with keys title, image, description, video, url,
     * @return array
     */
   public static function create(array $data): array
   {
       $mandotoryKeys = ["title", "image", "description", "video", "url"];
       $incomingKey = array_keys($data);

       foreach ($mandotoryKeys as $key=>$value){
           if(!in_array($value, $incomingKey)){
               throw new \Exception("Key $value is missing in seo creation data");
           }
       }

       $finalBuild = [];

       if(!empty($data['title'])){
           $finalBuild["title"] = $data['title'];
           $finalBuild['DC.title'] = $data['title'];
           $finalBuild['og:title'] = $data['title'];
           $finalBuild['twitter:title'] = $data['title'];
       }

       if(!empty($data['description'])){
           $finalBuild['description'] = $data['description'];
           $finalBuild['keywords'] = implode(', ', explode(' ', $data['title'])).', '.
               implode(', ', explode(' ', $data['description']));
           $finalBuild['og:description'] = $data['description'];
           $finalBuild['twitter:description'] = $data['description'];
       }

       if(!empty('image')){
           $finalBuild['image'] = $data['image'];
           $finalBuild['og:image'] = $data['image'];
           $finalBuild['og:image:url'] = $data['image'];
           $finalBuild['og:image:secure_url'] = $data['url'];
           $list = explode('.', $data['image']);
           $finalBuild['og:image:type'] = "image/".end($list);
           $finalBuild['og:image:width'] = "400";
           $finalBuild['og:image:height'] = "300";
           $finalBuild['og:image:alt'] = $data['title'];
           $finalBuild['twitter:image'] = $data['image'];
       }

       if(!empty($data['url'])){
           $finalBuild['canonical'] = $data['url'];
           $finalBuild['og:url'] = $data['url'];
           $finalBuild['twitter:url'] = $data['url'];
           $finalBuild['url'] = $data['url'];
       }

       if(!empty($data['video'])){
           $finalBuild['video'] = $data['video'];
           $finalBuild['og:video'] = $data['video'];
       }


       $finalBuild['copyright'] = "Stream studios @".(new \DateTime('now'))->format("Y");
       $finalBuild['robots'] = "index, follow";
       $finalBuild['og:type'] = "website";
       $finalBuild['og:site_name'] = "Stream Studios";
       $finalBuild['twitter:card'] = "summary";


        return $finalBuild;
   }

   public static function updateSEO($token, $data): void
   {
       $oldData = (new SEOTags($token))->get();
       if(empty($oldData)){
           return;
       }

       $newData = array_merge($oldData, $data);
       $newData['url'] = $token;
       $seoData = SEOTags::create($newData);
       (new SEOTags($token))->data($seoData)->set();
   }

}