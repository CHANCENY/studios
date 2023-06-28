<?php

use GlobalsFunctions\Globals;

if((new Modules\Movies\Movie())->delete(Globals::get('movie'))){
    Globals::redirect(Globals::get('destination'));
}else{
    echo \Alerts\Alerts::alert('warning', "Movie not Found!");
}

?>