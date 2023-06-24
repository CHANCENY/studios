<?php use GlobalsFunctions\Globals;

@session_start();

if(Globals::method() === 'POST'){

    if(!empty(Globals::post('btn'))){
        $file = Globals::files('file');

        $filename = $file['name'];
        global $MAXFILESIZE;

        if($file['size'] > $MAXFILESIZE){
            echo "<h1>File size too large</h1>";
            exit;
        }
        $filePath = \FileHandler\FileHandler::saveFile($filename,  $file['tmp_name']);
        $list = explode('/', $filePath);
        $filePath = "Files/".end($list);

        $result = (new Modules\Imports\ImportHandler())->import(Globals::post('type'), $filePath);
    }
}

?>
<section class="container mt-5">
    <div class="w-50 m-auto">
        <form method="POST" action="<?php echo Globals::uri(); ?>" enctype="multipart/form-data" class="form">
            <div class="form-group">
                <label for="file">Excel File</label>
                <input type="file" class="form-control" name="file" id="file">
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" id="type" class="form-select">
                    <option value="">Choose type</option>
                    <option value="movies">Movies Import</option>
                    <option value="shows">Shows Import</option>
                </select>
            </div>
            <button class="btn btn-primary mt-5 bg-primary" name="btn" type="submit" id="b" value="imp">Import All</button>
        </form>
    </div>
</section>
