<?php

namespace Handlers;

use app\App;
use Modules\Modals\Details;
use Modules\Modals\Playing;
use Modules\Movies\Movie;
use Modules\Renders\ImageHandler;
use Modules\Shows\ShowsHandlers;
use function functions\config;

class Movies
{
    public function MovieDetails(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['movie_id'] ?? null;
            if(!empty($movieID))
            {
                $movieID = htmlspecialchars(strip_tags($movieID));
                if(!is_numeric($movieID))
                {
                    $data = (new Details($movieID))->load('movies')->entity();
                    if(!empty($data))
                    {
                        $data['title'] = html_entity_decode($data['title']);
                        $data['overview'] = html_entity_decode($data['overview']);
                        $image = explode("=", $data['image'] ?? "");
                        $image = end($image);
                        $image = (new ImageHandler($image))->loadImage()->getURL();
                        $image = str_starts_with($image, "https") ? $image : "https://$image";
                        $data['image'] = $image;
                        return [
                            'status'=>200,
                            'results'=> $data
                        ];
                    }
                    return [
                        'status'=>404,
                        'msg'=> "Movie not found"
                    ];
                }
                return [
                    'status'=>404,
                    'msg'=>"Movie ID is invalid"
                ];
            }
        }
        return [
            'status'=>404,
            'msg'=>"Movie not found"
        ];
    }

    public function MovieUrl(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['movie_id'] ?? 1;
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $entity = new Playing(new Details($movieID));
                $id = $entity->getMovie($movieID);
                if($id === false)
                {
                    $data['url'] = null;
                    return [
                        'status'=>200,
                        'results'=>$data
                    ];
                }
                $entity->load($id);
                $data['title'] = html_entity_decode($entity->getTitle());
                $data['duration'] = $entity->getDuration();
                $data['overview'] = $entity->overview();
                $data['url'] = $entity->getUrl();
                return [
                    'status'=>200,
                    'results'=>$data
                ];
            }
            return [
                'status'=>404,
                'msg'=> "Movie ID is invalid"
            ];
        }
        return [
            'status'=>404,
            'msg'=>"Movie not found"
        ];
    }

    public function movieTrailers(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['movie_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $data = (new Details($movieID))->load('movies')->getVideoTrailers();
                if(!empty($data))
                {
                    return [
                        'status'=>200,
                        'results'=>$data
                    ];
                }
                return [
                    'status'=>404,
                    'msg'=>"Movie trailers not found"
                ];
            }
        }
        return [
            'status'=>200,
            'msg'=>"Movie not found"
        ];
    }

    public function movieImages(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['movie_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $data = (new Details($movieID))->load('movies')->getMorePhotos();
                if(!empty($data))
                {
                    $final = [];
                    foreach ($data as $key=>$v)
                    {
                        $v['file_path'] = "https://image.tmdb.org/t/p/w500".$v['file_path'];
                        $final[] = $v;
                    }
                    return [
                        'status'=>200,
                        'results'=>$final
                    ];
                }
                return [
                    'status'=>404,
                    'msg'=>"Movie images not found"
                ];
            }
        }
        return [
            'status'=>200,
            'msg'=>"Movie not found"
        ];
    }

    public function movieRelated(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['movie_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $data = (new Details($movieID))->load('movies')->getYouMayLike();
                if(!empty($data))
                {
                    $final = [];
                    foreach ($data as $key=>$value)
                    {
                        $image = explode("=", $value['image'] ?? "");
                        $image = end($image);
                        $image = (new ImageHandler($image))->loadImage()->getURL();
                        $image = str_starts_with($image, "https") ? $image : "https://$image";
                        $value['image'] = $image;
                        $value['trailers'] = explode(",", $value['trailers'] ?? "");
                        $final[] = $value;
                    }
                    return [
                        'status'=>200,
                        'results'=>array_values($final)
                    ];
                }
                return [
                    'status'=>404,
                    'msg'=>"Movie related not found"
                ];
            }
        }
        return [
            'status'=>200,
            'msg'=>"Movie not found"
        ];
    }

    public function movieReviews(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['movie_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $data = (new Details($movieID))->load('movies')->reviews();
                if(!empty($data))
                {
                    return [
                        'status'=>200,
                        'results'=>$data
                    ];
                }
                return [
                    'status'=>404,
                    'msg'=>"Movie reviews not found"
                ];
            }
        }
        return [
            'status'=>200,
            'msg'=>"Movie not found"
        ];
    }

    public function movieOthers(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['movie_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $m = (new Details($movieID))->load('movies');
                $data['vote'] = $m->getVotes();
                $data['rate'] = $m->getRating();
                $data['popularity'] = $m->getPopularity();
                $data['tmdb'] = $m->tmID();
                $data['genres'] = $m->getGenre();
                $data['country'] = $m->getCountry();
                $data['language'] = $m->getLanguage();
                if(!empty($data))
                {
                    return [
                        'status'=>200,
                        'results'=>$data
                    ];
                }
                return [
                    'status'=>404,
                    'msg'=>"Movie more info not found"
                ];
            }
        }
        return [
            'status'=>200,
            'msg'=>"Movie not found"
        ];
    }


    public function MoviesListing(App $myApp): array
    {
        $listingMovies = [];
        $params = $myApp->getParamsData();
        $page = intval($params['page_number']) ?? 0;
        $listingMovies = (new Movie())->movies();
        $limit = intval(config('PAGERLIMIT')) ?? 12;
        $listingMovies = array_chunk($listingMovies,$limit);
        if(isset($listingMovies[$page])){
            $listingMovies = $listingMovies[$page];
            $newList = [];
            foreach ($listingMovies as $key=>$value)
            {
                if(gettype($value) === "array")
                {
                    $value['title'] = html_entity_decode($value['title'] ?? "");
                    $value['id'] = $value['movie_uuid'];
                    $value['date'] = $value['release_date'];
                    unset($value['description']);
                    unset($value['release_date']);
                    unset($value['movie_uuid']);
                    unset($value['movie_changed']);
                    unset($value['created']);
                    unset($value['movie_id']);
                    unset($value['url']);
                    unset($value['type']);
                    unset($value['related_movies']);
                    unset($value['image_id']);
                    unset($value['target_id']);
                    unset($value['genre_id']);
                    if(str_contains($value['url_image'], '?image='))
                    {
                        $list = explode('=', $value['url_image']);
                        $imageID = end($list);
                        $value['image'] ="https://". (new ImageHandler(trim($imageID)))->loadImage()->getURL();
                    }else{
                        $value['image'] = $value['url_image'];
                    }
                    unset($value['url_image']);
                    unset($value['movie_image']);
                    unset($value['genre_name']);
                    unset($value['duration']);

                    $newList[] = $value;
                }
            }
            return ['status'=>200, 'results'=>array_values($newList)];
        }
        return ['msg'=>"Pages ends at ".count($listingMovies) - 1, 'status'=>404];
    }
}