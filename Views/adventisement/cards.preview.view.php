<?php

use GlobalsFunctions\Globals;

$cards = \Datainterface\Selection::selectAll("advertisement_cards");
if(Globals::get("card")){
    $cards = \Datainterface\Selection::selectById("advertisement_cards", ['card_uuid'=> Globals::get('card')]);
}

?>
<section class="container bg-dark">
    <div class="m-auto bg-dark"><?php if(!empty($cards)): ?><?php foreach ($cards as $key=>$value): ?>
        <?php echo $value['card_body'] ?? null; ?>
    <?php endforeach; endif; ?></div>
</section>
