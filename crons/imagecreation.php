<?php

$curl = curl_init("http://localhost/eco.com/image-creation");
curl_exec($curl);
curl_close($curl);