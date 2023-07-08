<?php

namespace Modules\Request;

use Json\Json;
use Mailling\Mails;

class Request
{
    public function saveRequest($data, $type): bool
    {
        $storage = "request/$type/request_file.json";
        $json = new Json();
        $json->setStoreName($storage);
        return $json->save($data)->isError();
    }

    public function markDone($type, $key): bool
    {
        $data['request_status'] = 'old';
        $json = new \Json\Json();
        $json->setStoreName("request/$type/request_file.json");
        if($json->upDate($data,$key)->isError() === false){
            return true;
        }
        return false;
    }

    public function sendConfirmationDoneEmail($email, array $others): bool
    {
        $title = $others['title'] ?? $others['original_name'];
        $list = explode('@', $email);
        $message = "Hello {$list[0]},<br><br> You request for {$others['type']} have been processed.<br>
                    Flee free to watch it at. By searching {$title}
                   <br><br>Thank you.";

        $subject = "Request Process Confirmation Email";

        $data['message'] = $message;
        $data['subject'] = $subject;
        $data['user'] = [\functions\config('MAIL-NOTIFY'),$email];
        $data['altbody'] = "Thank you for reach out.";
        $data['attached'] = false;
        $data['reply'] = false;
        return Mails::send($data, "notify");
    }
}