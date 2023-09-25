<?php

$curl = curl_init("http://localhost/eco.com/stream-transform-link");
curl_exec($curl);
curl_close($curl);
echo \Alerts\Alerts::alert("info", "Cron image link transformed");