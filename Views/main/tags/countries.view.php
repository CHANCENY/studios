<?php use GlobalsFunctions\Globals;

@session_start();

$tags = (new \Modules\Modals\Tags($tag))->getTags();
?>
<?php if(!empty($tags) && isset($tags['movies']) && isset($tags['shows'])): ?>
<!-- accordion -->
<div class="container">
    <div class="accordion" id="accordion">
        <div class="row">
            <div class="col-12 col-xl-6">
                <div class="accordion__card">
                    <div class="card-header" id="headingOne">
                        <button type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <span><?php echo ucfirst($tag); ?> - Movies</span>
                        </button>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <table class="accordion__list">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo ucfirst($tag); ?></th>
                                    <th>Search</th>
                                </tr>
                                </thead>

                                <tbody><?php foreach ($tags['movies'] as $key=>$value): ?>
                                <tr>
                                    <th><?php echo $key; ?></th>
                                    <td><?php echo $value; ?></td>
                                    <td><a href="<?php echo "$url?$params=$key&type=movies"; ?>" rel="index" title="<?php echo "Movies from $value"; ?>">Search movies by <?php echo $tag.' '. $value ?? null; ?></a></td>
                                </tr>
                                <?php endforeach; ?></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="accordion__card">
                    <div class="card-header" id="headingTwo">
                        <button type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                            <span><?php echo ucfirst($tag); ?> - Shows</span>
                        </button>
                    </div>

                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <table class="accordion__list">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo ucfirst($tag); ?></th>
                                    <th>Search</th>
                                </tr>
                                </thead>

                                <tbody><?php foreach ($tags['shows'] as $key=>$value): ?>
                                    <tr>
                                        <th><?php echo $key; ?></th>
                                        <td><?php echo $value; ?></td>
                                        <td><a href="<?php echo "$url?$params=$key&type=shows"; ?>" rel="index" title="<?php echo "Shows from $value"; ?>">Search shows by <?php echo $value ?? null; ?></a></td>
                                    </tr>
                                <?php endforeach; ?></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end accordion -->
<?php endif; ?>
