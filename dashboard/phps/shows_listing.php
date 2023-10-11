<?php

$shows = (new \groups\GroupShows())->showsListings();
echo \ApiHandler\ApiHandlerClass::stringfiyData(['results'=>$shows]);
exit;
