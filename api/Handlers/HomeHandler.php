<?php

namespace Handlers;

use app\App;
use GlobalsFunctions\Globals;

class HomeHandler
{
    public function homePage(App $myApp = (new App())): array|string
    {
        $route = Globals::get("content");
        $route = empty($route) ? "welcome" : $route;
        $contents = file_get_contents("components/contents/$route.html");

        return
            "<section class='container'>
              <h1>Welcome to stream studios Developer Center</h1>
              <p>Stream studios provides very simple to use api which you can integrate on your website or app. our api is
                  fast, reliable and with all videos you need.</p>
              <p>APIs here are congeries as follows</p>
               <div class='container'>
                 <div class='row'>
                    <div class='col-3 p-3 border rounded'>
                      <div class='accordion' id='accordionExample'>
    <div class='accordion-item'>
        <h2 class='accordion-header' id='headingOne'>
            <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOne' aria-expanded='false' aria-controls='collapseOne'>
                Endpoints Categories
            </button>
        </h2>
        <div id='collapseOne' class='accordion-collapse collapse show' aria-labelledby='headingOne' data-bs-parent='#accordionExample'>
            <div class='accordion-body'>
                <ul>
                    <li>
                        Session handling endpoints
                        <ul>
                            <li><a href='?content=session-creation'>Create Session</a></li>
                            <li><a href='?content=regenerate-session'>Regenerate Session</a></li>
                            <li><a href='?content=closing-session'>Closing Session</a></li>
                        </ul>
                    </li>
                    <li>
                        Movies endpoints
                        <ul>
                            <li><a href='?content=movies-listing'>Movies listing</a></li>
                            <li><a href='?content=movie-details'>Movie details</a></li>
                            <li><a href='?content=movie-images'>Movie images</a></li>
                            <li><a href='?content=movie-reviews'>Movies reviews</a></li>
                            <li><a href='?content=movie-related'>Movie related</a></li>
                            <li><a href='?content=movie-url'>Movie url</a></li>
                            <li><a href='?content=movie-trailers'>Movie trailers</a></li>
                        </ul>
                    </li>
                    <li>
                        Series endpoints
                        <ul>
                            <li><a href='?content=series-listing'>Series listing</a></li>
                            <li><a href='?content=series-details'>Series details</a></li>
                            <li><a href='?content=series-images'>Series images</a></li>
                            <li><a href='?content=series-reviews'>Series reviews</a></li>
                            <li><a href='?content=series-related'>Series related</a></li>
                            <li><a href='?content=series-url'>Series url</a></li>
                            <li><a href='?content=series-trailers'>Series trailers</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
                    </div>
                   <div class='col-sm-9 border-start rounded'>
                     $contents
                   </div>
                 </div>
               </div>
             </section>"
            ;
    }
}
