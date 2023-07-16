<?php use GlobalsFunctions\Globals;

@session_start();


if(!empty(Globals::get('key')) && !empty(Globals::get('type'))){
    $defualtKey = Globals::get('key');
    $type = Globals::get('type');

    $data['request_status'] = 'old';
    $json = new \Json\Json();
    $json->setStoreName("request/$type/request_file.json");
    if($json->upDate($data,$defualtKey)->isError() === false){
        Globals::redirect(Globals::get('destination'));
        exit;
    }else{
        echo \Alerts\Alerts::alert('danger', "Failed to mark done @ $defualtKey");
        exit;
    }
}




$json = new \Json\Json();
$json->setStoreName('request/tv_shows/request_file.json');
$shows = $json->getDataInStorage();

$json = new \Json\Json();
$json->setStoreName('request/movies/request_file.json');
$movies = $json->getDataInStorage();


$displayShow = [];
$moviesShow = [];
foreach ($shows as $key=>$value){
    if($value['request_status'] === 'new'){
        $displayShow[] = $value;
    }
}

foreach ($movies as $key=>$value){
    if($value['request_status'] === 'new'){
        $moviesShow[] = $value;
    }
}
?>
<section class="container mt-lg-5">
    <div class="m-auto">
        <h1 class="text-center text-white-50">Shows requested by Users</h1>
        <table class="table mt-4">
            <thead class="text-white-50">
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Release date</th>
                <th>Remark</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody class="text-white-50">
            <?php if(!empty($displayShow)): ?>
            <?php foreach ($displayShow as $key=>$value): ?>
                <tr>
                    <td><img style="width: 7rem;" src="https://image.tmdb.org/t/p/w500/<?php echo $value['poster_path'] ?? $value['backdrop_path']; ?>"></td>
                    <td><?php echo $value['original_name'] ?? null; ?></td>
                    <td><?php echo (new DateTime($value['first_air_date']))->format('M d, Y'); ?></td>
                    <td><?php echo $value['show_episodes_description']; ?></td>
                    <td><a class="mx-3" href="tm-tv-save?<?php echo $value['query'] ?? null; ?>">Add</a>
                        <a href="<?php echo Globals::url().'?key='.$value['defaultKey'].'&type=tv_shows&destination='.Globals::url(); ?>">Mark done</a></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<section class="container mt-lg-5">
    <div class="m-auto">
        <h1 class="text-white-50 text-center">Movie requested by Users</h1>
        <table class="table mt-4">
            <thead class="text-white-50">
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Release date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody class="text-white-50">
            <?php if(!empty($moviesShow)): ?>
                <?php foreach ($moviesShow as $key=>$value): ?>
                    <tr>
                        <td><img style="width: 7rem;" src="https://image.tmdb.org/t/p/w500/<?php echo $value['poster_path'] ?? $value['backdrop_path']; ?>"></td>
                        <td><?php echo $value['title'] ?? null; ?></td>
                        <td><?php echo (new DateTime($value['release_date']))->format('M d, Y'); ?></td>
                        <td><a class="mx-3" href="tm-form-add?<?php echo $value['query'] ?? null; ?>">Add</a>
                            <a href="<?php echo Globals::url().'?key='.$value['defaultKey'].'&type=movies&destination='.Globals::url(); ?>">Mark done</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
