<?php

use ApiHandler\ApiHandlerClass;
use GlobalsFunctions\Globals;
use Modules\Request\Request;

if(!empty(Globals::get('q'))){
    $search = Globals::get('q');
    $result = discover($search);
    $result['body']['results'] = array_merge($result['body']['results'] ?? [],  tv($search)['body']['results'] ?? []);

    echo ApiHandlerClass::stringfiyData($result);
    exit;
}

if(Globals::method() === 'POST' && !empty(Globals::post('request-submit')))
{
    //print_r($_POST);
    $type = isset($_POST['original_name']) ? 'tv_shows' : 'movies';

    $data = $_POST;
    $data['request_status'] = 'new';
    $result = (new Request())->saveRequest($data, $type);
    $time = (new DateTime('now'))->format('M d, Y');
    $title = $_POST['original_name'] ?? $_POST['title'];
    $release = $_POST['first_air_date'] ?? $_POST['release_date'];
    $image = "https://image.tmdb.org/t/p/w500".$_POST['backdrop_path'] ?? $_POST['poster_path'];
    $description = $_POST['overview'];
    $subject = "Request for ". $type === 'tv_shows' ? "Tv show" : "Movie";
    if($result !== true){
        $message = "<p>You have $subject 
                       Request sent: $time<br>
                       Request title: {$title}<br>
                       Request release date: {$release}<br>
                       Requested By: {$_POST['user_mail']}<br><br>
                       $description      
                    </p>
                    <img src='$image' style='width: 18rem;' alt='$title'>
                    ";
        $data['subject'] = $subject;
        $data['message'] = $message;
        $data['user'] = [\functions\config('MAIL-NOTIFY'),$_POST['user_mail']];
        $data['altbody'] = "Thank you for reach out.";
        $data['attached'] = false;
        $data['reply'] = false;

        if(\Mailling\Mails::send($data, 'notify')){
            echo \Alerts\Alerts::alert("info","Email of your request sent successfully. we will be in touch as soon as we have handled your request
             Thank you");
        }else{
            echo \Alerts\Alerts::alert('warning', "Failed to sent your request");
        }

    }
}


function discover(string $search): array
{$authToken = \functions\config('TMDB');
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.themoviedb.org/3/search/movie?query=$search&include_adult=false&language=en-US&page=1",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: $authToken",
            "accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    return [
        'error'=>$err,
        'body'=>json_decode($response, true)
    ];
}

function tv(string $search): array
{
    $authToken = \functions\config('TMDB');
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.themoviedb.org/3/search/tv?query=$search&include_adult=false&language=en-US&page=1",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: $authToken",
            "accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    return [
        'error'=>$err,
        'body'=> json_decode($response,  true)
    ];
}

?>
<?php if(empty(Globals::get('adult'))): ?>
<section class="container mt-lg-5" style="margin-bottom: 100%">
    <div class="m-auto float-lg-none">
        <input type="search" class="form-control float-end" name="search-request" id="search-request" placeholder="search movie/show to request">
    </div>
    <div class="d-inline-flex">
        <div id="request-search-boxes" class="row m-auto justify-content-center my-movies">

        </div>
    </div>
</section>
<?php else: ?>
<section class="container mt-lg-5">
    <div class="m-auto w-50">
        <h1>Request Confirmation Form</h1>
        <form class="form m-auto mt-lg-4" method="POST" action="<?php echo Globals::url(); ?>">
            <?php if(!empty(Globals::params())): ?>
            <?php  foreach (Globals::params() as $key=>$value): ?>
                <?php echo "<input type='hidden' name='$key' value='$value'>"; ?>
            <?php endforeach; ?>
            <?php endif; ?>
            <input type="hidden" name="query" value="<?php echo Globals::queryline() ?? null; ?>">
            <div class="form-group mb-4">
                <label>Your Email Address</label>
                <input type="email" class="form-control" name="user_mail" placeholder="Your email" required>
            </div>
            <div class="form-group">
                <label>
                    Type episodes you are looking for of show you selected from search result. (optional for movie)
                </label>
                <textarea class="form-control" name="show_episodes_description"></textarea>
            </div>
            <button class="btn btn-outline-light mt-4 w-100" name="request-submit" value="request" type="submit">Submit Request</button>
        </form>
    </div>
</section>
<?php endif; ?>
<script src="assets/my-styles/js/request-search.js"></script>
