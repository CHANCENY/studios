<?php

$episodes = (new \Modules\Episodes\Episode())->episodes();
$render = new \Modules\Renders\RenderHandler($episodes);
$episodes = $render->getOutPutRender();
shuffle($episodes);
?>
<section class="container mt-lg-5">
    <div class="m-auto">
        <div class="row"><?php if(!empty($episodes)): ?><?php \Core\Router::attachView('card', $episodes); ?><?php endif; ?>
        </div><?php Modules\Renders\RenderHandler::pager($render); ?>
    </div>
</section>