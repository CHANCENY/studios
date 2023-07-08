<?php
use GlobalsFunctions\Globals;

$termsAndCondition = file_get_contents(Globals::root()."/Views/policy/teramandcondition.html");
$termsAndCondition = str_replace("Streamtape", "Streamstudio", $termsAndCondition);
$termsAndCondition = str_replace("streamtape", "Streamstudio", $termsAndCondition);

?>
<section>
 <?php echo $termsAndCondition; ?>
</section>
