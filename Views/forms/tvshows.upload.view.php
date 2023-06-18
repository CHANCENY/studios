<?php
use GlobalsFunctions\Globals;
use Modules\Shows\ShowsHandlers;

(new \Modules\StorageDefinitions\Storage());

/**
 * bring in all shows
 */
$availableShows = (new ShowsHandlers())->shows();

/**
 * listen for post
 */
if(Globals::method() === 'POST'){

    if(isset($_POST['submit-show'])){
        $shows = new ShowsHandlers();
        $result = $shows->addShow();
        if(!$result['error']){
            $message = "<p>Added show with the following info</p><ul>
                   <li>Show id: {$result['show']}</li><li>Season id: {$result['season']}</li>
                   <li>Episode id: {$result['episode']}</li><li>Related id: {$result['related']}</li></ul>";
            echo \Alerts\Alerts::alert('info', $message);
        }else{
            echo \Alerts\Alerts::alert('warning', $result['message']);
        }
    }else{
        $data = \ApiHandler\ApiHandlerClass::getPostBody();
        $id = $data['show_id'];
        $result = (new ShowsHandlers())->getSeason($id);
        echo \ApiHandler\ApiHandlerClass::stringfiyData($result);
        exit;
    }

}

?>
<section class="container mt-lg-5">
    <div class="w-50 m-auto">
        <form class="form text-white" id="form" method="POST" action="<?php echo Globals::uri(); ?>">
            <div class="form-group mb-4">
                <label for="title">Tv Show Title</label>
                <input type="text" name="title" class="form-control" id="title" placeholder="Show title">
                <span>Option only if shows is available in Available Shows field</span>
            </div>
            <div class="form-group mb-4">
                <label type="available">Available Shows (optional)</label>
                <select name="available" id="available" class="form-select">
                  <option value="">Select</option>
                    <?php foreach ($availableShows as $key=>$value): ?>
                        <option value="<?php echo $value['show_id'] ?? null; ?>"><?php echo $value['title'] ?? null; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="description">Tv Show Description</label>
                <textarea name="description" class="form-control" cols="4" rows="4" id="description" placeholder="Show description"></textarea>
                <span>Optional if shows already exist</span>
            </div>

            <div class="form-group mb-4">
                <label for="season">Season</label>
                <input type="text" name="season" id="season" class="form-control" placeholder="Season name">
                <span>Option only if season is available in Available Seasons field</span>
            </div>

            <div class="form-group mb-4">
                <label type="season-available">Available Seasons (optional)</label>
                <select name="season-available" id="season-available" class="form-select">
                    <option value="">Select</option>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="season">Release date</label>
                <input type="text" name="release-date" id="season" class="form-control" placeholder="Season release date">
                <span>Option only if season is available in Available Seasons field</span>
            </div>

            <div class="form-group mb-4">
                <label for="episode">Episode</label>
                <input type="text" name="episode" id="episode" class="form-control" placeholder="Episode name">
                <input type="url" name="episodeurl" id="episode" class="form-control mt-2" placeholder="Episode Url">
                <input type="text" name="duration" id="episode" class="form-control mt-2" placeholder="Episode Duration">
                <input type="text" name="type" id="episode" class="form-control mt-2" placeholder="Episode type">
            </div>

            <div class="form-group">
                <label for="related">Related Shows</label>
                <select name="shows-related[]" id="related" multiple class="form-select">
                    <option value="">Select</option>
                    <?php foreach ($availableShows as $key=>$value): ?>
                        <option value="<?php echo $value['show_id'] ?? null; ?>"><?php echo $value['title'] ?? null; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary w-100 mt-5" name="submit-show">Save Show</button>
            </div>
        </form>
    </div>
</section>
<section>
    <script type="application/javascript">
        const available = document.getElementById('available');
        if(available !== null){
            available.addEventListener('change', (e)=>{
                const v = e.target.value;
                const xhr = new XMLHttpRequest();
                const url = window.location.href;
                xhr.open('POST',url, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onload = function (){
                    if(this.status === 200){
                        const data = JSON.parse(this.responseText);
                        if(data.length > 0){
                            data.forEach((item)=>{
                                let op = document.createElement('option');
                                op.value = item.season_id;
                                op.textContent = item.season_name;

                                const avS = document.getElementById('season-available');
                                if(avS !== null){
                                    avS.appendChild(op);
                                }
                            })
                        }
                    }
                }
                xhr.send(JSON.stringify({show_id: v}));
            })
        }
    </script>
</section>

