<?php
//$result = (new \Modules\Genres\Genre())->filteredListGenre();

?>
<section class="container w-100 mt-2">
    <ul class="row text-white-50 d-inline-flex list-unstyled"><?php if(!empty($result)): ?><?php foreach ($result as $key=>$value): ?>
        <li class="col"><?php echo $value ?? null; ?></li>
        <?php endforeach; ?><?php endif; ?></ul>
</section>
