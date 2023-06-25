<?php

$callingFrom = $options['from'] ?? "";
$list = $options['list'] ?? 0;
?>
<?php if(!str_contains($callingFrom, 'index') && $list > 0 ): ?>
<div class="float-end w-25 rounded my-block">
     <h4 class="display-7 text-white-50 text-center">Genre</h4>
    <ul class="list-group">
        <li class="list-group-item text-white-50 my-list">
            One item
        </li>

        <li class="list-group-item text-white-50 my-list">
            One too
        </li>
    </ul>
</div>
<?php endif; ?>
