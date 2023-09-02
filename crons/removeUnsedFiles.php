<?php
$curl = curl_init("http://localhost/eco.com/remove-unsed-files");
curl_setopt($curl, CURLOPT_HTTPHEADER,['Content-Type: application/json']);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl);
curl_close($curl);
echo $result;
exit;
?>