<?php


use GlobalsFunctions\Globals;

$copyrightText = file_get_contents(Globals::root()."/Views/policy/copyright.html");
$copyrightText = str_replace("Streamtape", "Streamstudio", $copyrightText);
$copyrightText = str_replace("streamtape", "Streamstudio", $copyrightText);
echo $copyrightText;
?>