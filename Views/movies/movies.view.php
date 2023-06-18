<?php

$movieShows = "query";
if(isset($fromIndex) && $fromIndex === true){
    $movieShows = "rrr";
}else{
    \Core\Router::attachView('tags',['title'=> 'Movies']);
}

?>

<section class="w-100 mt-lg-5 ms-lg-3">
    <div class="row m-auto justify-content-center">
        <div class="card bg-dark mx-1 mt-3" style="width: 18rem;">
            <img src="https://stream.quickapistorage.com/Files/logo.png" class="card-img-top m-auto zoom" alt="kk">
            <div class="card-body">
                <p class="card-text text-white-50"><a href="movie-stream?movie=my movie title" class="text-decoration-none text-white-50">Hevalier 2023</a></p>
                <p class="card-text text-white-50">Jan 23, 2023</p>
            </div>
        </div>

        <div class="card bg-dark mx-1 mt-3" style="width: 18rem;">
            <img src="https://stream.quickapistorage.com/Files/logo.png" class="card-img-top m-auto zoom" alt="kk">
            <div class="card-body">
                <p class="card-text text-white-50">Chevalier 2023</p>
                <p class="card-text text-white-50">Jan 23, 2023</p>
            </div>
        </div>

        <div class="card bg-dark mx-1 mt-3" style="width: 18rem;">
            <img src="https://stream.quickapistorage.com/Files/logo.png" class="card-img-top m-auto zoom" alt="kk">
            <div class="card-body">
                <p class="card-text text-white-50">The Wrath of Becky 2023</p>
                <p class="card-text text-white-50">Jan 23, 2023</p>
            </div>
        </div>

        <div class="card bg-dark mx-1 mt-3" style="width: 18rem;">
            <img src="https://stream.quickapistorage.com/Files/logo.png" class="card-img-top m-auto zoom" alt="kk">
            <div class="card-body">
                <p class="card-text text-white-50">Extraction 2 2023</p>
                <p class="card-text text-white-50">Jan 23, 2023</p>
            </div>
        </div>

    </div>
</section>