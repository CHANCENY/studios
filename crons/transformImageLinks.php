<?php

$curl = curl_init("https://streamstudios.online/stream-transform-link");
curl_exec($curl);
curl_close($curl);
echo \Alerts\Alerts::alert("info", "Cron image link transformed");