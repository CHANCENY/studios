<?php

namespace Handlers;

use app\App;
use Datainterface\Selection;
use Modules\Modals\Details;
use Modules\Renders\ImageHandler;
use Modules\Shows\ShowsHandlers;
use function functions\config;

class Shows
{
    public function ShowsDetails(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['show_id'] ?? null;
            if(!empty($movieID))
            {
                $movieID = htmlspecialchars(strip_tags($movieID));
                if(!is_numeric($movieID))
                {
                    $data = (new Details($movieID))->load('shows')->entity();
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
                        'msg'=> "Show details not found"
                    ];
                }
                return [
                    'status'=>404,
                    'msg'=>"Show ID is invalid"
                ];
            }
        }
        return [
            'status'=>404,
            'msg'=>"Movie not found"
        ];
    }

    public function ShowsTrailers(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['show_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $data = (new Details($movieID))->load('shows')->getVideoTrailers();
                if(!empty($data))
                {
                    return [
                        'status'=>200,
                        'results'=>$data
                    ];
                }
                return [
                    'status'=>404,
                    'msg'=>"Show trailers not found"
                ];
            }
        }
        return [
            'status'=>200,
            'msg'=>"Show not found"
        ];
    }

    public function ShowsImages(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['show_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $data = (new Details($movieID))->load('shows')->getMorePhotos();
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
                    'msg'=>"Show images not found"
                ];
            }
        }
        return [
            'status'=>200,
            'msg'=>"Show not found"
        ];
    }

    public function ShowsRelated(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['show_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $data = (new Details($movieID))->load('shows')->getYouMayLike();
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
                    'msg'=>"Shows related not found"
                ];
            }
        }
        return [
            'status'=>200,
            'msg'=>"Show not found"
        ];
    }

    public function ShowsReviews(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['show_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $data = (new Details($movieID))->load('shows')->reviews();
                if(!empty($data))
                {
                    return [
                        'status'=>200,
                        'results'=>$data
                    ];
                }
                return [
                    'status'=>404,
                    'msg'=>"Show reviews not found"
                ];
            }
        }
        return [
            'status'=>200,
            'msg'=>"Show not found"
        ];
    }

    public function ShowsOthers(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['show_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $m = (new Details($movieID))->load('shows');
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
                    'msg'=>"Show more info not found"
                ];
            }
        }
        return [
            'status'=>200,
            'msg'=>"Show not found"
        ];
    }

    public function ShowsSeasons(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['show_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $id = (new Details($movieID))->load('shows')->id();
                $show = (new ShowsHandlers())->getSeasons($id);
                if(!empty($show))
                {
                    $final = [];
                    foreach ($show as $key=>$value)
                    {
                        $image = explode("=", $value['season_image'] ?? "");
                        $image = end($image);
                        $image = (new ImageHandler($image))->loadImage()->getURL();
                        $image = str_starts_with($image, "https") ? $image : "https://$image";

                        $final[] = [
                            'title'=>$value['season_name'],
                            'air_date'=>$value['air_date'],
                            'episode_count'=>$value['episode_count'],
                            'description'=>$value['description'],
                            'season_number'=>$value['season_number'],
                            'season_id'=>$value['season_uuid'],
                            'season_image'=>$image
                        ];
                    }
                    if(!empty($final))
                    {
                        return [
                            'status'=>200,
                            'results'=>array_values($final)
                        ];
                    }
                    return [
                        'status'=>404,
                        'msg'=>"Show seasons not found"
                    ];
                }
            }
        }
        return [
            'status'=>200,
            'msg'=>"Show seasons not found"
        ];
    }

    public function ShowsSeason(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['season_id'];
            if(!is_numeric($movieID) && !empty($movieID))
            {
                $season = Selection::selectById('seasons',['season_uuid'=>$movieID]);
                if(!empty($season))
                {
                    $final = [];
                    foreach ($season as $key=>$value)
                    {
                        $image = explode("=", $value['season_image'] ?? "");
                        $image = end($image);
                        $image = (new ImageHandler($image))->loadImage()->getURL();
                        $image = str_starts_with($image, "https") ? $image : "https://$image";

                        $final[] = [
                            'title'=>$value['season_name'],
                            'air_date'=>$value['air_date'],
                            'episode_count'=>$value['episode_count'],
                            'description'=>$value['description'],
                            'season_number'=>$value['season_number'],
                            'season_id'=>$value['season_uuid'],
                            'season_image'=>$image
                        ];
                    }
                    if(!empty($final))
                    {
                        return [
                            'status'=>200,
                            'results'=>array_values($final)
                        ];
                    }
                    return [
                        'status'=>404,
                        'msg'=>"Show season not found"
                    ];
                }
            }
        }
        return [
            'status'=>200,
            'msg'=>"Show season not found"
        ];
    }

    public function ShowsEpisodes(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params))
        {
            $movieID = $params['season_id'];
            $season = Selection::selectById('seasons',['season_uuid'=>$movieID]);
            if(!is_numeric($movieID) && !empty($movieID) && !empty($season))
            {
                $episodes = (new ShowsHandlers())->getEpisodes($season[0]['season_id']);
                if(!empty($episodes))
                {
                    $final = [];
                    foreach ($episodes as $key=>$value)
                    {
                        $image = "";
                        if(str_contains($value['epso_image'], '='))
                        {
                            $image = explode("=", $value['epso_image'] ?? "");
                            $image = end($image);
                            $image = (new ImageHandler($image))->loadImage()->getURL();
                            $image = str_starts_with($image, "https") ? $image : "https://$image";
                        }else{
                            $image = $value['epso_image'];
                        }
                        if(isset($value['publish']) && $value['publish'] === "yes")
                        {
                            $final[] = [
                                'title'=>$value['title'],
                                'air_date'=>$value['air_date'],
                                'episode_number'=>$value['epso_number'],
                                'description'=>$value['epso_description'],
                                'url'=>$value['url'],
                                'duration'=>$value['duration'],
                                'episode_image'=>$image,
                                'episode_id' => $value['episode_uuid']
                            ];
                        }
                    }
                    if(!empty($final))
                    {
                        return [
                            'status'=>200,
                            'results'=>array_values($final)
                        ];
                    }
                    return [
                        'status'=>404,
                        'msg'=>"Show season not found"
                    ];
                }
            }
        }
        return [
            'status'=>200,
            'msg'=>"Show season not found"
        ];
    }

    public function ShowsEpisode(App $myApp): array
    {
        $params = $myApp->getParamsData();
        if(!empty($params)) {
            $movieID = $params['episode_id'];
            $episode = Selection::selectById('episodes', ['episode_uuid' => $movieID]);
            if (!is_numeric($movieID) && !empty($movieID) && !empty($episode)) {
                $data = (new ShowsHandlers())->getEpisode($episode[0]['episode_id']);
                if(!empty($data))
                {
                    $data = $data[0] ?? $data;
                    $image = "";
                    if(str_contains($data['epso_image'], '='))
                    {
                        $image = explode("=", $data['epso_image'] ?? "");
                        $image = end($image);
                        $image = (new ImageHandler($image))->loadImage()->getURL();
                        $image = str_starts_with($image, "https") ? $image : "https://$image";
                    }else{
                        $image = $data['epso_image'];
                    }
                    unset($data['type']);
                    unset($data['season_id']);
                    unset($data['changed']);
                    unset($data['created']);
                    unset($data['publish']);
                    unset($data['episode_id']);
                    unset($data['episode_uuid']);
                    $data['description'] = $data['epso_description'];
                    $data['image'] = $image;
                    $data['number'] = $data['epso_number'];
                    unset($data['epso_description']);
                    unset($data['epso_image']);
                    unset($data['epso_number']);

                    return [
                        'status'=>200,
                        'results'=>$data
                    ];
                }
                return [
                    'status'=>404,
                    'msg'=>"Show episode not found"
                ];
            }
        }
        return [
            'status'=>404,
            'msg'=>"Show episode not found"
        ];
    }

    public function ShowsListing(App $myApp): array
    {
        $listingShows = [];
        $params = $myApp->getParamsData();
        $page = intval($params['page_number']) ?? 0;
        $listingShows = (new ShowsHandlers())->shows();
        $limit = intval(config('PAGERLIMIT')) ?? 12;
        $listingShows = array_chunk($listingShows,$limit);
        if(isset($listingShows[$page])){
            $listingShows = $listingShows[$page];
            $newList = [];
            foreach ($listingShows as $key=>$value)
            {
                if(gettype($value) === "array")
                {
                    $value['title'] = html_entity_decode($value['title'] ?? "");
                    $value['id'] = $value['show_uuid'];
                    $value['date'] = $value['release_date'];
                    unset($value['release_date']);
                    unset($value['show_uuid']);
                    unset($value['show_changed']);
                    unset($value['created']);
                    unset($value['show_id']);
                    unset($value['description']);
                    if(str_contains($value['show_image'], '?image='))
                    {
                        $list = explode('=', $value['show_image']);
                        $imageID = end($list);
                        $value['image'] ="https://". (new ImageHandler(trim($imageID)))->loadImage()->getURL();
                    }else{
                        $value['image'] = $value['show_image'];
                    }
                    unset($value['show_image']);
                    $newList[] = $value;
                }
            }
            return ['status'=>200, 'results'=>array_values($newList)];
        }
        return ['msg'=>"Pages ends at ".count($listingShows) - 1, 'status'=>404];
    }

}