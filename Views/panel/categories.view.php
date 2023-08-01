<?php

use Modules\TypeModerator\EntityCategories;

$limit =  \functions\config("PAGERLIMIT");
$percent = \functions\config("PERCENT");

$categories = (new EntityCategories())->categories();
$info = [];
foreach ($categories as $key=>$value){

    $info["Shows"][$value['category_name'] ?? ""] = (new EntityCategories())->get(0, $value['category_name'],"Shows");
}

foreach ($categories as $key=>$value){
    $info["Movies"][$value['category_name'] ?? ""] = (new EntityCategories())->get(0, $value['category_name'],"Movies");
}
?>

<div class="mt-lg-5 text-white-50">
    <h2>Categories statistics</h2>
    <p>This is statistics of categories which is determined by item per page and PERCENT</p>
</div>
<?php foreach ($info as $key=>$value): ?>
<div class="border-4">
    <h3 class="text-white-50 mb-lg-4 mt-lg-5"><?php echo $key; ?></h3><?php foreach ($value as $k=>$v): ?>
    <div class="mt-lg-4">
        <h6 class="text-white-50"><?php echo $k; ?></h6>
        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="<?php echo count(array_chunk($v, $limit)); ?>" aria-valuemin="0" aria-valuemax="<?php echo $percent; ?>">
            <div class="progress-bar" style="width: <?php echo count(array_chunk($v, $limit)); ?>%"></div>
            <span><?php echo count(array_chunk($v, $limit)).'%'; ?></span>
        </div>
    </div><?php endforeach; ?>
</div>
<?php endforeach; ?>
