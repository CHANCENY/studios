<?php

ob_clean();
ob_flush();

print_r((new \Modules\Imports\Additionals())->addRemainingInfo(20));
exit;