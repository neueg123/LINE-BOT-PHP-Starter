<?php
$access_token = 'ZmiuqedWlnrDp3v8VFhTd5JMc8hib1ehkZQWqzxW4W7rSbG4z4hdsO5K/YuL7K2OMngjtGKxQE1+3ex3SMD+HzyiHvAzJAjj2zBB8twvBPjTJidWq7MxRbtpdjZuhVX9Dlt+bXZv9qWBD6AZPv/2GgdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;
