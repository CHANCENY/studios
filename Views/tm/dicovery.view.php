<?php use ApiHandler\ApiHandlerClass;
use GlobalsFunctions\Globals;

@session_start();

$searchString = Globals::get('string');
$type = Globals::get('type');

if($type === 'discover'){
    $result = discover($searchString);
    echo ApiHandlerClass::stringfiyData($result);
    exit;
}

function discover(string $search): array
{
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
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJiZDExNTI3ZDkwYmIxMWVhYTI0NGE2MzUwNTQwYWQyMSIsInN1YiI6IjY0OTZkMDg1YjM0NDA5MDBmZmViZTVlOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.VIHnt5aWjblsCAB__DD9hQEWzZblm0X5BjmtOtJBbJY",
            "accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    return [
        'error'=>$err,
        'body'=>json_decode($response)
    ];
}