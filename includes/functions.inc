<?php
namespace functions;

use Datainterface\mysql\SelectionLayer;

/**
 * @param $time
 * @return string|void
 */
function to_time_ago( $datetime, $full = false )
{
    $now = new \DateTime;
    $ago = new \DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

/**
 * @param string $name name found in table api_configuration_keys_value
 * @return string value return found in api_configuration_keys_value
 */
function config(string $name): string
{
    $result = (new SelectionLayer())->setTableName('api_configuration_keys_value')
        ->setKeyValue(['name'=>$name])
        ->selectBy()
        ->rows();
    return $result[0]['value'] ?? "";
}