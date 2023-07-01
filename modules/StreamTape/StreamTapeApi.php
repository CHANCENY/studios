<?php

namespace Modules\StreamTape;

use function functions\config;

class StreamTapeApi
{
    private string $apiLoginKey;
    private string $apiPassword;

    private string $apiBaseURL;

    private array $apiDirectories;
    /**
     * @var true
     */
    private bool $isFileUploaded;
    /**
     * @var true
     */
    private bool $isFolderCreated;

    public function __construct()
    {
        $this->apiLoginKey = config('API-LOGIN-KEY');
        $this->apiPassword = config('API-PASSWORD');
        $this->apiDirectories['TV'] = config('API-TV-DIRECTORY');
        $this->apiDirectories['MOVIE'] = config('API-MOVIE-DIRECTORY');
        $this->apiBaseURL = config('API-BASEURL');
        $this->isFolderCreated = false;
        $this->isFileUploaded = false;
    }

    public function checkKeys(): void
    {
        $parent = $this->apiDirectories['MOVIE'];
        $movie = "https://u6.safelock.pw:183/d/akhnpluz3ebnqcl46h4pf4ugahktxbvqebgnnohvrrtmqtedps3aehaenkavyx4hpcfyc7ny/tfpdl-yuhtmyflg2348wd.mkv";
        echo $this->streamTapeMovieUpload($movie,'sample2',$parent);
    }

    public function streamTapeMovieUpload($movie_url, $new_folder, $parent_folder): string
    {
       $folderId = $this->createFolder($new_folder, $parent_folder);
        $fid = $this->uploadMovie($movie_url,$folderId);
        return $this->findFile($movie_url,$folderId);
    }

    public function uploadMovie(string $movieUrl, $folder): string
    {
        $fid = "";
        while (true){
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "$this->apiBaseURL/remotedl/add?login=$this->apiLoginKey&key=$this->apiPassword&url=$movieUrl&folder=$folder",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ]);

            $result = curl_exec($curl);
            $data = json_decode($result, true);
            if(!empty($data['result']['id'])){
                $this->isFileUploaded = true;
                $fid = $data['result']['id'];
                break;
            }

        }
        return $fid;
    }

    public function createFolder(string $newFolder, string $parentFolder): string
    {
        $folderId = "";
        while (true){
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "$this->apiBaseURL/file/createfolder?login=$this->apiLoginKey&key=$this->apiPassword&name=$newFolder&pid=$parentFolder",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ]);

            $result = curl_exec($curl);
            $data = json_decode($result, true);
            if(!empty($data['result']['folderid'])){
                $this->isFolderCreated = true;
                $folderId = $data['result']['folderid'];
                break;
            }
        }
        return $folderId;

    }

    public function downLoadLink($fid): string
    {
        $ticket = "";
        $data = "";
        $down = "";
        while(true){
            if(empty($ticket)){
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "$this->apiBaseURL/file/dlticket?file=$fid",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                ]);

                $result = curl_exec($curl);
                $data = json_decode($result, true);
            }

            if(!empty($data['result']['ticket'])){
                $ticket = $data['result']['ticket'];

                print_r($ticket);
                exit;
                //calling for link
                $curl1 = curl_init();
                curl_setopt_array($curl1, [
                    CURLOPT_URL => "$this->apiBaseURL/file/dl?file=$fid&ticket=$ticket",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                ]);

                $result = curl_exec($curl1);
                $data1 = json_decode($result, true);
                print_r($data1);
                if(!empty($data1['result']['url'])){
                    $down = $data1['result']['url'];
                    break;
                }
            }
        }
        return $down;

    }


    public function findFile($movie_url_sent, $folderId): string
    {
        $link = "";
        while (true){
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "$this->apiBaseURL/file/listfolder?login=$this->apiLoginKey&key=$this->apiPassword&folder=$folderId",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ]);
            $result = curl_exec($curl);
            $data = json_decode($result, true);

            if(!empty($data))
            {
                $files = $data['result']['files'] ?? [];
                if(!empty($files)){
                    foreach ($files as $key=>$value){
                        $list = explode('/',$movie_url_sent);
                        $loc = end($list);
                        if(str_contains( $value['link'], $loc)){
                            $link = $value['link'];
                            break;
                        }
                    }
                    break;
                }
            }
        }
        return $link;
    }
}