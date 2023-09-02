<?php use GlobalsFunctions\Globals;
use Modules\Modals\SiteMapGenerator;

@session_start();

ob_clean();
ob_flush();

$page = Globals::get('page');
$page = empty($page) ? 0 : intval($page);

$sobj = new SiteMapGenerator($page);
$data = $sobj->getSiteMap();
$site = $sobj->buildSiteMapFile($data);
if(!empty($site)){
    header("Content-Type: application/xml");
    echo $site;
    exit;
}
