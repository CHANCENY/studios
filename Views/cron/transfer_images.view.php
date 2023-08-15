<?php

@session_start();

use Modules\Imports\ImagesMigrator;

(new ImagesMigrator())->moveImages(50);

?>