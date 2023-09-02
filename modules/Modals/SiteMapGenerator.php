<?php

namespace Modules\Modals;

use Datainterface\Query;
use Datainterface\Selection;
use GlobalsFunctions\Globals;
use function functions\config;

class SiteMapGenerator
{
    private array $sitemap;
    private array $dbconfig;
    private bool $makePage;

    public function __construct(private readonly int $page = 0)
    {
        if($this->page > 0){
            $this->dbconfig = ['limit'=>1000, 'offset'=>1000 * $this->page];
        }else{
            $this->dbconfig = ['limit'=>1000, 'offset'=>1000];
        }
        $this->sitemap = [];
        $this->dicision();
        $this->buildUp();
    }

    private function buildUp(): void
    {

        if($this->page <= 0){
            $query = "SELECT * FROM site_map ORDER BY sid DESC LIMIT {$this->dbconfig['limit']}";
        }else{
            $query = "SELECT * FROM site_map ORDER BY sid DESC LIMIT {$this->dbconfig['limit']} OFFSET {$this->dbconfig['offset']}";
        }
        $tokens = Query::query($query);
        foreach ($tokens as $key=>$item){
            if(!empty($item) && gettype($item) === 'array'){

                $url = parse_url($item['url']);


                $path = $url['path'];
                if(!empty($path)){
                    $view = Globals::findViewByUrl(substr($path,1));
                    if(!empty($view) && $view['view_role_access'] !== "private" &&
                        $view['view_role_access'] !== "administrator" &&
                        $view['view_role_access'] !== "moderator" && isset($view['view_path_absolute'])  &&
                        str_contains( $view['view_path_absolute'], "Views/main/")
                    ){
                        $this->sitemap[] = [
                            'loc'=> trim($item['url']) ,
                            'lastmod'=> (new \DateTime($item['created']))->format("c"),
                            'changefreq'=>"Always"
                        ];
                    }
                }
            }

        }
    }

    public function getSiteMap(): array
    {
        return $this->sitemap;
    }

    public function buildSiteMapFile($data): string
    {
        if($this->makePage === true){
            $site = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
            $site .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
            $site .= $this->pages()."</sitemapindex>";
            return $site;
        }

        $site = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($data as $key=>$value){
            $allowed = $this->notAllowedChecker($value['loc']);
            if($allowed === false){
                $site .= "<url><loc><![CDATA[{$value['loc']}]]></loc><lastmod>{$value['lastmod']}</lastmod><changefreq>{$value['changefreq']}</changefreq><priority>1.0</priority></url>".PHP_EOL;
            }
        }
        return $site."</urlset>";
    }

    public function pages(): string
    {
        $data = Selection::selectAll("site_map");
        $chunks = array_chunk($data,$this->dbconfig['limit']);
        $total = count($chunks);
        $line = "";
        $currentURL =Globals::protocal()."://".Globals::serverHost().Globals::url();
        for ($i = 1; $i <= $total - 1; $i++){
            $path = $currentURL."?page=$i";
            $now = (new \DateTime('now'))->format('Y-m-d\TH:i:sP');
           $line .= '<sitemap>
                       <loc>'.$path.'</loc>
                       <lastmod>'.$now.'</lastmod>
                     </sitemap>';
        }
        return $line;
    }

    private function notAllowedChecker($url): bool
    {
        $list = config("SITEMAPE") ?? "mailto:|tel:";
        $list = explode('|', $list);
        foreach ($list as $key=>$value){
            if(strpos($url, $value)){
                return true;
            }
        }
        return false;
    }

    private function dicision(): void
    {
        $this->makePage = false;
        $page = Globals::get("page");
        if(empty($page)){
            $data = Selection::selectAll("site_map");
            $list = array_chunk($data,$this->dbconfig['limit']);
            if(count($list) >= 2){
               $this->makePage = true;
            }
        }
    }
}