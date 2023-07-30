<?php
 use GlobalsFunctions\Globals;

$position = Globals::get('page');
$pagination = $render->getPositions();
$previous = 0;
$next = 0;
if(!empty($position)){
    $previous = intval($position) - 1;
    $next = intval($position) + 1;
}
?>
<?php if(!empty($pagination) && count($pagination) > 1): ?>
    <nav aria-label="..." class="mt-lg-5 w-100 mb-lg-5 pager-container">
        <ul class="pagination m-auto" style="width: fit-content">
            <?php if(!empty($position)): ?>
                <li class="page-item">
                    <a class="page-link p-lg-3 mobi-mbri-arrow-prev float-start" href="<?php echo Globals::url(); ?>?page=<?php echo strval($previous); ?>"><span class="mobi-mbri-arrow-prev"></span></a>
                </li>
            <?php endif; ?>
            <?php $i = 0; foreach ($pagination as $key=>$page): ?><?php if($i < 4): ?>
                <li class="page-item <?php echo $page == $position ? 'active' : null; ?>">
                    <a class="page-link pager-auto-link" href="<?php echo Globals::url(); ?>?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                </li>
            <?php endif; ?><?php $i++; endforeach; ?>
            <?php if(!empty($position)): ?>
                <li class="page-item">
                    <a class="page-link mobi-mbri-arrow-next p-lg-3 float-end" href="<?php echo Globals::url(); ?>?page=<?php echo $next; ?>"><span class="mobi-mbri-arrow-next"></span></a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
