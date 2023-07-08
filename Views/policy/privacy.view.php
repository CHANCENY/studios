<?php

use GlobalsFunctions\Globals;

$privacy = file_get_contents(Globals::root()."/Views/policy/privacy.html");
$privacy = str_replace("Streamtape", "Streamstudio", $privacy);
$privacy = str_replace("streamtape", "Streamstudio", $privacy);

echo $privacy;
?>