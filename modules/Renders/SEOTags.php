<?php

namespace Modules\Renders;

use Core\Router;
use Datainterface\Database;
use Datainterface\Delete;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Selection;
use FileHandler\FileHandler;
use GlobalsFunctions\Globals;
use Json\Json;
use Modules\Movies\Movie;
use Modules\Shows\ShowsHandlers;
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
                try{
                    throw new \Exception("Key $value is missing in seo creation data");
                }catch(\Exception $e){
                    $data[$value] = "";
                }

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

        if(!empty($data['image'])){

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

    public static function findUnSavedSEO(): string
    {
        $currentURL = Globals::protocal()."://".Globals::serverHost().Globals::uri();
        $uri = Globals::uri();

        if(str_contains($uri, '?')){

            $list = explode("?", $uri);

            $one = $list[1] ?? "";
            $first = explode('=', $one)[0];
            $temp = [];
            if($first === "movie-id"){
                $temp['table'] = "movies";
                $key = explode("=", $one);
                $key = explode("&", end($key))[0];
                $temp['id'] = $key;
            }
            if($first === "series-id"){
                $temp['table'] = "tv_shows";
                $key = explode("=", $one);
                $key = explode("&", end($key))[0];
                $temp['id'] = $key;
            }
            if($first === "w"){
                $temp['table'] = "episodes";
                $key = explode("=", $one);
                $key = explode("&", end($key))[0];
                $temp['id'] = $key;
            }
            if($first === "m"){
                $temp['table'] = "movies";
                $key = explode("=", $one);
                $key = explode("&", end($key))[0];
                $temp['id'] = $key;
            }
            if($first === "se"){
                $temp['table'] = "seasons";
                $key = explode("=", $one);
                $key = explode("&", end($key))[0];
                $temp['id'] = $key;
            }
            if($first === "page"){
                $key = explode("=", $one);
                $key = explode("&", end($key))[0];

                $table = explode('/', $list[0]);
                if(end($table) === "movies"){
                    $table = "movieList";
                }
                elseif (end($table) === "tv-shows"){
                    $table = "showList";
                }
                elseif (end($table) === "individual-episode"){
                    $table = "episodeList";
                }
                $temp['table'] = "episodeList";
                $temp['id'] = $key;
                $fullData = SEOTags::getDataToSEO($temp);
                if(!isset($fullData['title'])){
                    return $fullData[0];
                }
            }
            if($first === "play"){
                $key = explode("=", $one);
                $list = explode("&", $key[1]);
                $temp['id'] = $list[0] ?? "";
                $key = explode("&", end($key))[0];
                $temp['table'] = $key === 'episode' ? "episodes" : "movies";

            }
            $fullData = SEOTags::getDataToSEO($temp);
            if(empty($fullData)){
                return "";
            }
            $fullData['url'] = $currentURL;
            $token = SEOTags::getToken($currentURL);
            $seoData = (new SEOTags($token))->get();
            if(empty($seoData)){
                $seo = SEOTags::create($fullData);
                (new SEOTags($token))->data($seo)->set();
                return (new SEOTags($token))->process()->seo();
            }
            return (new SEOTags($token))->process()->seo();
        }
        else{
            $list = explode('/', $uri);
            $view_url = end($list);
            $view = Globals::findViewByUrl($view_url);

            $seo['title'] = $view['view_name'] ?? null;
            $seo['description'] = $view['view_description'] ?? null;
            $seo['image'] = &$image;
            $seo['video'] = "";
            $seo['url'] = $currentURL;
            $image = FileHandler::findFile("logo.png");
            if(!empty($image)){
                $parts = explode('/', $image);
                $image = Globals::protocal().'://'.Globals::serverHost().'/Files/'.end($parts);
            }

            $token = SEOTags::getToken($currentURL);
            $seoData = (new SEOTags($token))->get();
            if(empty($seoData)){
                $seo = SEOTags::create($seo);
                (new SEOTags($token))->data($seo)->set();
                return (new SEOTags($token))->process()->seo();
            }
            return (new SEOTags($token))->process()->seo();
        }
    }

    public static function getDataToSEO($temp): array
    {
        $return = [];
        if(!isset($temp['table'])){
            return [];
        }

        $type = "website";

        if($temp['table'] === "movies")
        {
            $data = Selection::selectById('movies', ['movie_uuid'=>$temp['id']]);
            $return['title'] = $data[0]['title'] ?? null;
            $return['description'] = $data[0]['description'] ?? null;
            $return['video'] = $data[0]['url'] ?? null;
            $image = explode("=", $data[0]['movie_image'] ?? "");
            $id = end($image);
            $path = (new ImageHandler($id))->loadImage()->getURL();
            $imageUrl = "";
            if(!empty($path)){
                $imageUrl = "https://".$path;
                $type = "video.movie";
            }
            $return['image'] = $imageUrl;
        }
        elseif ($temp['table'] === "tv_shows")
        {
            $data = Selection::selectById('tv_shows', ['show_uuid'=>$temp['id']]);
            $return['title'] = $data[0]['title'] ?? null;
            $return['description'] = $data[0]['description'] ?? null;
            $return['video'] = $data[0]['url'] ?? null;

            $image = explode("=", $data[0]['show_image'] ?? "");
            $id = end($image);
            $imageUrl = "";
            if(!empty($id)){
                $obj = new ImageHandler($id);
                $obj->loadImage();
                $imageUrl = $obj->getURL();
                if(!empty($imageUrl)){
                    $imageUrl = "https://".$imageUrl;
                    $type = "video.movie";
                }
            }
            $return['image'] = $imageUrl;
        }
        elseif ($temp['table'] === "episodes")
        {

            $data = Selection::selectById('episodes', ['episode_uuid'=>$temp['id']]);

            $season = Selection::selectById('seasons', ['season_id'=>$data[0]['season_id'] ?? 0]);
            $show = Selection::selectById('tv_shows', ['show_id'=>$season[0]['show_id'] ?? 0]);

            $title = $show[0]['title'] ?? null;
            $ept = $data[0]['title'] ?? null;

            $return['title'] = $title. " - ".$ept." Ep: ".$data[0]['epso_number'] ?? null;
            $return['description'] = $data[0]['epso_description'] ?? null;
            $return['video'] = $data[0]['url'] ?? null;
            $m = $data[0]['epso_image'] ?? $show[0]['show_image'] ?? "";
            $image = explode("=", $m);
            $id = end($image);
            $path = (new ImageHandler($id))->loadImage()->getURL();
            $imageUrl = "";
            if(!empty($path)){
                $imageUrl = "https://".$path;
                $type = "video.movie";
            }
            $return['image'] = $imageUrl;
        }
        elseif ($temp['table'] === 'seasons')
        {
            $data = Selection::selectById('seasons', ['season_uuid'=>$temp['id']]);

            $show = Selection::selectById('tv_shows', ['show_id'=>$data[0]['show_id'] ?? 0]);
            $title = $show[0]['title'] ?? null;
            $st = $data[0]['title'];

            $return['title'] = $title.' - '.$st." SE: ".$data[0]['season_number'];
            $return['description'] = $data[0]['description'] ?? null;
            $return['video'] = $data[0]['url'] ?? null;
            $return['image'] = $data[0]['season_image'] ?? null;
        }
        elseif ($temp['table'] === "showList"){
            $shows = (new ShowsHandlers())->shows();
            $render = new \Modules\Renders\RenderHandler($shows);
            $tvShows = $render->getOutPutRender();

            $normal = "";
            $ogs = "";
            foreach ($tvShows as $key=>$value){
                $home = Globals::protocal().'://'.Globals::serverHost().'/series-overview-details?series-id='.$value['show_uuid'] ?? null;
                $normal .= "<meta name='title' content='{$value['title']}' />".PHP_EOL;
                $normal .= "<meta name='description' content='{$value['description']}' />".PHP_EOL;
                $normal .= "<meta name='image' content='{$value['show_image']}' />".PHP_EOL;
                $normal .= "<meta name='url' content='{$home}' />";
                $ogs .= "<meta property='og:image' content='{$value['show_image']}' />".PHP_EOL;
                $ogs .=  '<meta property="og:image:width" content="300" />'.PHP_EOL;
                $ogs .= '<meta property="og:image:height" content="300" />'.PHP_EOL;
                $ogs .= "<meta property='og:description' content='{$value['description']}' />".PHP_EOL;
                $ogs .= '<meta property="og:site_name" content="Stream Studios" />';
                $ogs .= "<meta property='og:title' content='{$value['title']}' />";
                $ogs .= "<meta property='og:url' content='{$home}' />";
                $ogs .= "<meta property='og:type' content=$type />";

            }
            $return[] = $normal.$ogs;
        }
        elseif ($temp['table'] === "movieList"){
            $movies = (new Movie())->movies();
            $render = new \Modules\Renders\RenderHandler($movies);
            $movie = $render->getOutPutRender();
            $normal = "";
            $ogs = "";
            foreach ($movie as $key=>$value){
                $home = Globals::protocal().'://'.Globals::serverHost().'/film-overview-details?movie-id='.$value['movie_uuid'] ?? null;
                $normal .= "<meta name='title' content='{$value['title']}' />".PHP_EOL;
                $normal .= "<meta name='description' content='{$value['description']}' />".PHP_EOL;
                $normal .= "<meta name='image' content='{$value['url_image']}' />".PHP_EOL;
                $normal .= "<meta name='url' content='{$home}' />";
                $ogs .= "<meta property='og:image' content='{$value['url_image']}' />".PHP_EOL;
                $ogs .=  '<meta property="og:image:width" content="300" />'.PHP_EOL;
                $ogs .= '<meta property="og:image:height" content="300" />'.PHP_EOL;
                $ogs .= "<meta property='og:description' content='{$value['description']}' />".PHP_EOL;
                $ogs .= '<meta property="og:site_name" content="Stream Studios" />';
                $ogs .= "<meta property='og:title' content='{$value['title']}' />";
                $ogs .= "<meta property='og:url' content='{$home}' />";
                $ogs .= "<meta property='og:type' content=$type />";
                $ogs .= "<meta property='og:video' content='{$value['url']}' />";

            }
            $return[] = $normal.$ogs;
        }
        elseif ($temp['table'] === "episodeList"){
            $return['title'] = "Show Episodes Easy fast way to watch";
            $return['image'] = "https://streamstudios.online/Files/logo.png";
            $return['video'] = "";
            $return['url'] = "https://streamstudios.online/individual-episodes?page=".$temp['id'];
            $return['description'] = "Find latest episodes you need for your shows";
        }
        return $return;
    }

}