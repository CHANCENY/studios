<?php

namespace groups;

use Datainterface\Insertion;
use GlobalsFunctions\Globals;
use User\User;

class Notifications
{
    public function __call(string $name, array $arguments)
    {
        $user = (new User());
        $uid = Globals::user()[0]['uid'] ?? 0;

        if($name === "movieUploaded")
        {
            $user = (new User());
            $uid = Globals::user()[0]['uid'] ?? 0;
            $data['event_name'] = "Movie upload";
            $arg = implode(",", $arguments);
            $data['event_description'] = "New movie upload at streamstudios by user 
                                           <a href='/profile?user=$uid'>{$user->firstName()} {$user->lastName()}</a>
                                           <p>Info $arg}</p>";
            $data['event_sent_status'] = 0;
            Insertion::insertRow("event_news", $data);
        }

        if($name === "movieUpdated")
        {
            $data['event_name'] = "Movie Upldated";
            $arg = implode(",", $arguments);
            $data['event_description'] = "Movie Updated at streamstudios by user 
                                           <a href='/profile?user=$uid'>{$user->firstName()} {$user->lastName()}</a>
                                           <p>Info $arg}</p>
                                           ";
            $data['event_sent_status'] = 0;
            Insertion::insertRow("event_news", $data);
        }

        if($name === "movieDeleted")
        {
            $data['event_name'] = "Movie Deleted";
            $arg = implode(",", $arguments);
            $data['event_description'] = "Movie Deleted at streamstudios by user 
                                           <a href='/profile?user=$uid'>{$user->firstName()} {$user->lastName()}</a>
                                           <p>Info $arg}</p>";
            $data['event_sent_status'] = 0;
            Insertion::insertRow("event_news", $data);
        }

        if($name === "imageUploaded")
        {
            $data['event_name'] = "Image file uploaded";
            $arg = implode(",", $arguments);
            $data['event_description'] = "Image file uploaded at streamstudios by user 
                                           <a href='/profile?user=$uid'>{$user->firstName()} {$user->lastName()}</a>
                                           <p>Info $arg}</p>";
            $data['event_sent_status'] = 0;
            Insertion::insertRow("event_news", $data);
        }

        if($name === "imageDeleted")
        {
            $data['event_name'] = "Image file deleted";
            $arg = implode(",", $arguments);
            $data['event_description'] = "Image file deleted at streamstudios by user 
                                           <a href='/profile?user=$uid'>{$user->firstName()} {$user->lastName()}</a>
                                           <p>Info $arg}</p>";
            $data['event_sent_status'] = 0;
            Insertion::insertRow("event_news", $data);
        }
    }
}