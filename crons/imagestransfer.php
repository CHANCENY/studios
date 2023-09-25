<?php

use Modules\Imports\ImagesMigrator;

$curl = curl_init("https://streamstudios.online/tranfering-images-permanent");
curl_exec($curl);
curl_close($curl);
echo \Alerts\Alerts::alert("info", "Cron run Images transferred");



