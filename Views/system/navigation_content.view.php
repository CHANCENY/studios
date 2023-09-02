<?php use GlobalsFunctions\Globals;

@session_start();

if(Globals::method() === "POST" && !empty(Globals::post('navigation-help')))
{
    $paragraphs = Globals::post('paragraphs');
    $videos = Globals::files('videos');
    $images = Globals::files('images');
    $title = Globals::post('title');

    $paragraphList = explode("\n\r", $paragraphs);
    $newParagraphs = [];
    foreach ($paragraphList as $key=>$value){
        $newParagraphs[] = "<p class='faq__text'>".trim($value)."</p>";
    }
    $paragraphs = implode("", $newParagraphs);
    $images = imagesUpload($images);
    $videos = videosUpload($videos);

    $data['paragraphs'] = $paragraphs;
    $data['images'] = implode(',', $images);
    $data['videos'] = implode(',', $videos);
    $data['title'] = $title;
    if((new \Modules\Imports\NavigationHelp(''))->saveNavigationContent($data)){
        Globals::redirect('stream-ccpanel');
        exit;
    }
}


function imagesUpload($images): array
{
    $imageURL = [];
    for ($i = 0; $i < count($images['name']); $i++){
        $filename = $images['name'][$i];
        $list = explode('.', $filename);

        $array = ['jpg', 'jpeg', 'png'];
        if(in_array(end($list), $array)){
            $target = "sites/files/navigation/help/images/";
            if(!is_dir($target)){
                mkdir($target, 7777, true);
            }
            up:
            $file = $target.\Json\Json::uuid().'.'.end($list);
            if(file_exists($file)){
                goto up;
            }

            if(move_uploaded_file($images['tmp_name'][$i], $file)){
                $uuid = \Json\Json::uuid();
                $spl = new \SplFileInfo($file);
                $data['image_extension'] = $spl->getExtension();
                $data['image_name'] = $spl->getFilename();
                $data['image_path'] = $spl->getRealPath();
                $data['image_size'] = $spl->getSize();
                $data['image_url'] = Globals::serverHost()."/".$file;
                $data['image_uuid'] = $uuid;
                $imageURL[] = $uuid;
                \Datainterface\Insertion::insertRow('images_managed', $data);
            }
        }
    }
    return $imageURL;
}

function videosUpload($videos): array
{
    $videosUrl = [];
    for ($i = 0; $i < count($videos['name']); $i++){
        $filename = $videos['name'][$i];
        $list = explode('.', $filename);
        $array = ['mp4', 'webm'];
        if(in_array(end($list), $array)){
            $target = "sites/files/navigation/help/videos/";
            if(!is_dir($target)){
                mkdir($target, 7777, true);
            }
            up:
            $file = $target.\Json\Json::uuid().'.'.end($list);
            if(file_exists($file)){
                goto up;
            }

            if(move_uploaded_file($videos['tmp_name'][$i], $file)){
                $uuid = \Json\Json::uuid();
                $spl = new \SplFileInfo($file);
                $data['image_extension'] = $spl->getExtension();
                $data['image_name'] = $spl->getFilename();
                $data['image_path'] = $spl->getRealPath();
                $data['image_size'] = $spl->getSize();
                $data['image_url'] = Globals::serverHost()."/".$file;
                $data['image_uuid'] = $uuid;
                $videosUrl[] = $uuid;
                \Datainterface\Insertion::insertRow('images_managed', $data);
            }
        }
    }
    return $videosUrl;
}

?>
<section class="container mt-lg-5">
    <div class="w-100 m-auto">
        <h1 class="text-center text-white-50">Upload Navigation help materials</h1>
        <form class="form mt-4 w-75 m-auto text-white-50" method="POST" action="<?php echo Globals::url(); ?>" enctype="multipart/form-data">
            <div class="form-group mt-4">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>

            <div class="form-group mt-4">
                <label for="images">Images</label>
                <input type="file" id="images" name="images[]" class="form-control" multiple>
            </div>

            <div class="form-group mt-4">
                <label for="videos">Videos</label>
                <input type="file" name="videos[]" id="videos" class="form-control" multiple>
            </div>

            <div class="form-group mt-4">
                <label for="text">Paragraphs</label>
                <textarea type="" name="paragraphs" class="form-control" rows="10" cols="10"></textarea>
            </div>
            <div class="form-group d-block mt-4">
                <button type="submit" name="navigation-help" value="ns" class="btn btn-outline-light">Save</button>
            </div>
        </form>
    </div>
</section>
