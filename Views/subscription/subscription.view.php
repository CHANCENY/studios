<?php

use ApiHandler\ApiHandlerClass;
use GlobalsFunctions\Globals;
use Modules\NewAlerts\SubcriberNews;

if(Globals::method() === 'POST')
{
    $subscriptionIncoming = ApiHandlerClass::getPostBody();
    $subscriptionEmail = $subscriptionIncoming['email'] ?? null;
    if(!empty($subscriptionEmail))
    {
        $subscriptionId = (new SubcriberNews('none'))->subscribeNews($subscriptionEmail);
        echo ApiHandlerClass::stringfiyData(['status'=>$subscriptionId]);
        exit;
    }
    exit;
}

if(!empty(Globals::get('id')) && !empty(Globals::get('line')))
{
    $id = Globals::get('id');
    $names = Globals::get('line');

    $alr = \Datainterface\Selection::selectById('subscribers',['subscribe_id'=>$id]);
    if(!empty($alr)){
        $names .= "|". $alr[0]['subscribe_name'] ?? null;
    }

    if(str_ends_with($names, '|')){
        $names = substr($names, 0, strlen($names) - 1);
    }


    $result = \Datainterface\Updating::update('subscribers',['subscribe_name'=>$names],['subscribe_id'=>$id]);
    echo ApiHandlerClass::stringfiyData(['result'=>$result]);
    exit;
}
?>
<section class="container mt-lg-4">
    <div class="m-auto">
        <table class="table text-white-50">
            <thead>
            <tr>
               <th>Subscription</th>
                <th>Summary</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
             <tr>
                 <td>New Movies Alerts</td>
                 <td>Subscribing to this alert you will be receiving email everytime we have uploaded a movie.</td>
                 <td>
                     <input type="checkbox" id="movie-alert" data='New Movies'>
                     <span>Check Here</span>
                 </td>
             </tr>

             <tr>
                 <td>New Shows Alerts</td>
                 <td>Subscribing to this alert you will be receiving email everytime we have uploaded a show.</td>
                 <td>
                     <input type="checkbox" id="show-alert" data='New shows'>
                     <span>Check Here</span>
                 </td>
             </tr>
             <tr>
                 <td>Episode Update Alerts</td>
                 <td>Subscribing to this alert you will be receiving email everytime we have uploaded a new episodes.</td>
                 <td>
                     <input type="checkbox" id="episode-alert" data='Episode Update'>
                     <span>Check Here</span>
                 </td>
             </tr>

             <tr>
                 <td>Season Update Alerts</td>
                 <td>Subscribing to this alert you will be receiving email everytime we have uploaded a new season.</td>
                 <td>
                     <input type="checkbox" id="season-alert" data='Season Update'>
                     <span>Check Here</span>
                 </td>
             </tr>
            </tbody>
        </table>
        <button id="save-subscriptions" class="btn btn-outline-light text-white-50">Save Subscriptions</button>
    </div>
</section>
