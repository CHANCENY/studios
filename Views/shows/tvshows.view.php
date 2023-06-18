<?php

$tvShows = "query";
if(isset($fromIndex) && $fromIndex === true){
    $tvShows = "rrr";
}else{
    \Core\Router::attachView('tags',['title'=> 'Tv Shows']);
}

?>

<section class="w-100 mt-lg-5 ms-lg-3">
    <div class="row m-auto justify-content-center">
        <div class="card bg-dark mx-1 mt-3" style="width: 18rem;">
            <img src="https://stream.quickapistorage.com/Files/logo.png" class="card-img-top m-auto zoom" alt="kk">
            <div class="card-body">
                <p class="card-text text-white-50"><a href="view-tv-show?show=the 100" class="text-decoration-none text-white-50">The 100</a></p>
                <p class="card-text text-white-50">Jan 23, 2023</p>
            </div>
        </div>

        <div class="card bg-dark mx-1 mt-3" style="width: 18rem;">
            <img src="https://stream.quickapistorage.com/Files/logo.png" class="card-img-top m-auto zoom" alt="kk">
            <div class="card-body">
                <p class="card-text text-white-50">The 100 | mp4 | season 1</p>
                <p class="card-text text-white-50">Jan 23, 2023</p>
            </div>
        </div>

        <div class="card bg-dark mx-1 mt-3" style="width: 18rem;">
            <img src="https://stream.quickapistorage.com/Files/logo.png" class="card-img-top m-auto zoom" alt="kk">
            <div class="card-body">
                <p class="card-text text-white-50">The 100 | mp4 | season 1</p>
                <p class="card-text text-white-50">Jan 23, 2023</p>
            </div>
        </div>

        <div class="card bg-dark mx-1 mt-3" style="width: 18rem;">
            <img src="https://stream.quickapistorage.com/Files/logo.png" class="card-img-top m-auto zoom" alt="kk">
            <div class="card-body">
                <p class="card-text text-white-50">The 100 | mp4 | season 1</p>
                <p class="card-text text-white-50">Jan 23, 2023</p>
            </div>
        </div>

    </div>
</section>