<?php use Datainterface\Insertion;
use Datainterface\Query;
use Datainterface\Updating;
use GlobalsFunctions\Globals;
use function functions\config;

@session_start();

$ignore_cache = config('CACHE_IGNORE');
$hasCached = \Modules\Renders\Cache::hasCached();

if(Globals::method() === "POST" && !empty(Globals::post('save-cache')))
{
    $urls = Globals::post('ignore_urls');
    $files = Globals::post('ignore_files');

    $oldURL = config('CACHE-IGNORE-URLS');
    $oldFILES = config('CACHE-IGNORE-FILES');

    $flagUrl = false;
    $flagFiles = false;
    if(!empty($oldFILES)){
        Updating::update('api_configuration_keys_value',['value'=>$files],['name'=>'CACHE-IGNORE-FILES']);
        echo \Alerts\Alerts::alert('info', 'Updated successfully');
        $flagFiles = true;
    }

    if(!empty($oldURL)){
        Updating::update('api_configuration_keys_value',['value'=>$urls],['name'=>'CACHE-IGNORE-URLS']);
        echo \Alerts\Alerts::alert('info', 'Updated successfully');
        $flagUrl = true;
    }

    if($flagUrl === false){
        if(Insertion::insertRow('api_configuration_keys_value', ['name'=>'CACHE-IGNORE-URLS', 'value'=>$urls]))
        {
            echo \Alerts\Alerts::alert('info', 'Saved URLS successfully');
        }
    }

    if($flagFiles === false){
       if(Insertion::insertRow('api_configuration_keys_value', ['name'=>'CACHE-IGNORE-FILES', 'value'=>$files]))
       {
           echo \Alerts\Alerts::alert('info', 'Saved FILES successfully');
       }
    }

    //image-creation,stream-transform-link,tranfering-images-permanent,remove-unsed-files,login-user-at-stream-studios,stream-studios-join,forgot-stream-studios-password
    //js,css,jpg,jpeg,png,json,txt,less,sacss,otf,map,html,php
}


if(!empty(Globals::get('ignore_caching'))){
    $value = Globals::get('ignore_caching');

    if(!empty($ignore_cache)){
        Updating::update('api_configuration_keys_value',['name'=>'CACHE_IGNORE','value'=>$value],['name'=>'CACHE_IGNORE']);
    }else{
        Insertion::insertRow('api_configuration_keys_value',['name'=>'CACHE_IGNORE','value'=>$value]);
    }
    Globals::redirect('cache-configure');
    exit;
}

if(!empty(Globals::get('config'))){
    if((new \Modules\Renders\Cache(Globals::uri()))->clearCached()){
        Globals::redirect('cache-configure');
        exit;
    }
}

$ignoreValue = (empty($ignore_cache) ? 'NO' : $ignore_cache === 'YES') ? 'NO' : 'YES';
$ignoreText = "CACHE CLOSE";
if($ignore_cache === 'YES'){
    $ignoreText = "CACHE START";
}
if(empty($ignore_cache)){
    $ignoreText = "CACHE START";
}
?>
<section class="container mt-4">
    <div class="w-75 m-auto">
        <div class="row">
            <a href="?ignore_caching=<?php echo $ignoreValue ?>" class="btn btn-outline-light"><?php echo $ignoreText; ?></a>
            <a href="?config=clear" class="btn btn-outline-light mt-2 mb-lg-4 <?php echo !$hasCached ? 'disabled' : null; ?>">CACHE CLEAR</a>
            <form class="form" method="POST" action="<?php echo Globals::uri(); ?>">
                <div class="form-group">
                    <label for="files-not">Files to Ignore (only ext (,))</label>
                    <textarea cols="10" class="form-control" rows="10" name="ignore_files"><?php echo config('CACHE-IGNORE-FILES'); ?></textarea>
                </div>
                <div class="form-group mt-4">
                    <label for="url-not">URL to Ignore (only ext (,))</label>
                    <textarea cols="10" class="form-control" id="url-not" rows="10" name="ignore_urls"><?php echo config('CACHE-IGNORE-URLS'); ?></textarea>
                </div>
                <div class="form-group mt-4">
                    <button type="submit" name="save-cache" value="save" class="btn btn-outline-light">Save</button>
                </div>
            </form>
        </div>
    </div>
</section>
