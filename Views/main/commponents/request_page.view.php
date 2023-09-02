<?php
use GlobalsFunctions\Globals;
use Modules\Request\Request;

if(Globals::method() === 'POST' && !empty(Globals::post('request-submit-2')))
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
            Globals::redirect('/home');
            exit;
        }else{
            $_SESSION['message-request'] = "Failed to request movie/ show";
            Globals::redirect(Globals::url());
            exit;
        }

    }
}


?>
<!-- page title -->
<section class="section section--first section--bg" data-bg="assets/main/img/section/section.jpg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section__wrap">
                    <!-- section title -->
                    <h2 class="section__title">Requests</h2>
                    <!-- end section title -->

                    <!-- breadcrumb -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb__item"><a href="home">Home</a></li>
                        <li class="breadcrumb__item breadcrumb__item--active">Requests</li>
                    </ul>
                    <!-- end breadcrumb -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- comments -->
<div class="col-12">
    <div style="text-align: center; color: whitesmoke; margin: 10px;">
        <p>
            To request a movie or show you have to search it using search field below. After that please scroll below
             Click one that you want this page will load, after that please fill up the form and submit the submit.
        </p>
        <p>
            <?php echo $_SESSION['message-request'] ?? null;   $_SESSION['message-request'] = ""; ?>
        </p>
    </div>
    <div class="comments">
        <form action="<?php echo Globals::uri(); ?>" id="form-request" class="form" method="POST">
            <input type="search" name="name"  id="search-request" class="form__input" placeholder="Search movie or show">
            <input type="text" name="user_mail" class="form__input" placeholder="Your Email" required>
            <textarea id="text" name="show_episodes_description" class="form__textarea" placeholder="Add episode description (optional for movies)"></textarea>
            <?php if(!empty(Globals::params())): ?>
                <?php  foreach (Globals::params() as $key=>$value): ?>
                    <?php echo "<input type='hidden' name='$key' value='$value'>"; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <button type="submit" name="request-submit-2" value="send" class="form__btn">Send Request</button>
        </form>
    </div>
</div>

<!-- catalog -->
<div class="catalog">
    <div class="container">
        <div class="row" id="request-search-boxes">

        </div>
    </div>
</div>
<!-- end catalog -->


<script src="assets/my-styles/js/request-search.js"></script>
