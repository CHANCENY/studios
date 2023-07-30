<?php


use Json\Json;
use Datainterface\Updating;
use Datainterface\Selection;
use Sessions\SessionManager;
use GlobalsFunctions\Globals;
use ApiHandler\ApiHandlerClass;
use Modules\Shows\ShowsHandlers;
use Modules\Renders\RenderHandler;

$shows = [];
$seasons = [];
$episodes = [];
$eids = [];

$message = "";


$renders = null;

if(!empty(Globals::get('delete'))){
    $showId = Globals::get('delete');
    if(!empty($showId)){
        $result = (new ShowsHandlers())->deleteShow($showId);
        if($result){
            echo \Alerts\Alerts::alert('info', "Show deleted Completely");
            Globals::redirect(Globals::url());
        }else{
            echo \Alerts\Alerts::alert('danger','Failed to delete show');
            Globals::redirect(Globals::url());
        }
    }
}

if(!empty(Globals::get('seasonId')) && !empty('action')){
    $d = Globals::get('action') === 'publish' ? 'yes' : 'no';
    Updating::update('tv_shows',['show_uuid'=>Json::uuid()], ['show_id'=>SessionManager::getSession('update-id')]);
    $query = "UPDATE episodes SET publish = :action WHERE season_id = :id";
    $result = \Datainterface\Query::query($query, ['id'=>Globals::get('seasonId'), 'action'=>$d]);
    echo ApiHandlerClass::stringfiyData(['status'=>empty($result)]);
    exit;
}


if(!empty(isset($_GET['episode_id']) && isset($_GET['url']) && isset($_GET['publish']))){
    $data = [
            'url'=>Globals::get('url'),
        'publish'=>Globals::get('publish')
    ];
    Updating::update('tv_shows',['show_uuid'=>Json::uuid()], ['show_id'=>SessionManager::getSession('update-id')]);
    $result = ShowsHandlers::updateEpisode($data,Globals::get('episode_id'));
    echo ApiHandlerClass::stringfiyData(['status'=>$result]);
    exit;
}

if(empty(Globals::get('show')) && empty(Globals::get('season'))){
    $shows = (new ShowsHandlers())->shows();

    if(Globals::get("search")){
        $lines = Globals::get('search');
        $shows = (new ShowsHandlers())->searchShow($lines);
        if(empty($shows)){
           echo \Alerts\Alerts::alert("info", "<p class='alert alert-info'>No search result found for $lines</p>");
        }
    }

    $renders = new RenderHandler($shows);
    $shows= $renders->getOutPutRender();
}
if(!empty(Globals::get('show'))){
   $showId = Globals::get('show');
   SessionManager::setSession('update-id', $showId);
   $seasons = (new ShowsHandlers())->getSeasons($showId);
}

if(!empty(Globals::get('season'))){
    $sid = Globals::get('season');
    $episodes = (new ShowsHandlers())->getEpisodes($sid);
    foreach ($episodes as $key=>$value){
        $eids[] = $value['episode_id'];
    }
}

?>
<?php if(!empty($shows)): ?>
<section class="container w-100 mt-lg-5 text-white-50">
    <form method="GET" action="#" class="form d-inline-flex float-end">
        <input type="search" name="search" class="form-control mx-3">
        <input type="submit" class="btn-outline-light btn" name="search-sub" value="search">
    </form>
    <table class="table">
        <thead class="text-white-50">
        <tr>
            <th>Show Title</th>
            <th>Release Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody class="text-white-50" id="total" data="<?php echo count($shows ?? []); ?>">
        <?php $i = 0; foreach ($shows as $key=>$value): ?>
                <tr>
                    <td><?php echo $value['title'] ?? null; ?></td>
                    <td><?php echo $value['release_date'] ?? null; ?></td>
                    <td>
                        <a href="<?php echo Globals::url()."?show=".$value['show_id'] ?? null; ?>">Edit</a>
                        <a href="<?php echo Globals::url()."?delete=".$value['show_id'] ?? null; ?>" class="ms-5" id="delete-link-<?php echo $i; ?>">Delete</a>
                    </td>
                </tr>
        <?php $i++; endforeach; ?>
        </tbody>
    </table>
    <?php Modules\Renders\RenderHandler::pager($renders); ?>
</section>
<script src="assets/my-styles/js/deleteshow.js"></script>
<?php endif; ?>

<?php if(!empty($seasons)): ?>
    <section class="container w-100 mt-lg-5 text-white-50">
        <table class="table">
            <thead class="text-white-50">
            <tr>
                <th>Season Name</th>
                <th>Release Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody class="text-white-50">
            <?php if(!empty($seasons)): ?>
                <?php foreach ($seasons as $key=>$value): ?>
                    <tr>
                        <td><?php echo $value['season_name'] ?? null; ?></td>
                        <td><?php echo $value['air_date'] ?? null; ?></td>
                        <td><a href="<?php echo Globals::url()."?season=".$value['season_id'] ?? null; ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>

<?php if(!empty($episodes)): ?>
    <section class="container w-100 mt-lg-5 text-white-50">
        <div class="d-inline-flex float-end" id="msg"></div>
        <table class="table">
            <thead class="text-white-50">
            <tr>
                <th>Episode Title</th>
                <th>Episode Number</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody class="text-white-50" id="all" data="<?php echo implode(',', $eids); ?>">
            <?php if(!empty($episodes)): ?>
                <?php foreach ($episodes as $key=>$value): ?>
                    <tr>
                        <td><?php echo $value['title'] ?? null; ?></td>
                        <td><?php echo $value['epso_number'] ?? null; ?></td>
                        <td>
                            <form method="POST" id="edit-form-<?php echo $value['episode_id'] ?? null; ?>" action="<?php echo Globals::url(); ?>">
                                <input type="hidden" id="id-episode-<?php echo $value['episode_id'] ?? null; ?>" name="episode_id" value="<?php echo $value['episode_id'] ?? null; ?>">
                                <select name="publish" id="publish-<?php echo $value['episode_id'] ?? null; ?>">
                                    <option value="">Publish</option>
                                    <?php if($value['publish'] === 'yes'): ?>
                                        <option value="yes" selected="selected">Yes</option>
                                        <option value="no">No</option>
                                    <?php else: ?>
                                        <option value="yes">Yes</option>
                                        <option value="no" selected="selected">No</option>
                                    <?php endif; ?>
                                </select>
                                <input type="url" id="url-<?php echo $value['episode_id'] ?? null;?>" name="url" placeholder="url" value="<?php echo $value['url'] ?? null; ?>">
                                <input type="submit" name="edit-episode" id="edit-episode-<?php echo $value['episode_id'] ?? null;?>" value="Save Changes">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <button id="un-publish" class="btn btn-outline-light" type="button" data="<?php echo Globals::get('season') ?? null;  ?>">Unpublished All</button>
        <button id="publish" class="btn btn-outline-light" type="button" data="<?php echo Globals::get('season') ?? null;  ?>">Published All</button>
    </section>
<script src="assets/my-styles/js/url_updating.js"></script>
<?php endif; ?>

