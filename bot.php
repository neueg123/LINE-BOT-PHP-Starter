<?php

namespace LINE\LINEBot\KitchenSink\EventHandler\MessageHandler;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\KitchenSink\EventHandler;

use Predis\Client;

class TextMessageHandler implements EventHandler
{

    private $bot;

    private $logger;

    private $req;

    private $textMessage;

    private $redis;

    public function __construct($bot, $logger, \Slim\Http\Request $req, TextMessage $textMessage)
    {
        $this->bot = $bot;
        $this->logger = $logger;
        $this->req = $req;
        $this->textMessage = $textMessage;
        $this->redis = new Client(getenv('REDIS_URL'));
    }

    public function handle()
    {
        $TEACH_SIGN = '==';
        $text = $this->textMessage->getText();
        $text = trim($text);
        # Remove ZWSP
        $text = str_replace("\xE2\x80\x8B", "", $text);
        $replyToken = $this->textMessage->getReplyToken();

        if ($text == 'บอท') {
            $this->bot->replyText($replyToken, $out =
                "ใช้ $TEACH_SIGN เพื่อสอนเราได้นะ\nเช่น สวัสดี" . $TEACH_SIGN . "สวัสดีชาวโลก");
            return true;
        }

        $sep_pos = strpos($text, $TEACH_SIGN);
        if ($sep_pos > 0) {
            $text_arr = explode($TEACH_SIGN, $text, 2);
            if (count($text_arr) == 2) {
                $this->saveResponse($text_arr[0], $text_arr[1]);
            }
            return true;
        }

        $re = $this->getResponse($text);
        $re_count = count($re);
        if ($re_count > 0) {
            // Random response.
            $randNum = rand(0, $re_count - 1);
            $response = $re[$randNum];
            $this->bot->replyText($replyToken, $response);
            return true;
        }
        return false;
    }

    private function saveResponse($keyword, $response)
    {
        $this->redis->lpush("response:$keyword", $response);
    }

    private function getResponse($keyword)
    {
        return $this->redis->lrange("response:$keyword", 0, -1);
    }
}
 
$strAccessToken = "ZmiuqedWlnrDp3v8VFhTd5JMc8hib1ehkZQWqzxW4W7rSbG4z4hdsO5K/YuL7K2OMngjtGKxQE1+3ex3SMD+HzyiHvAzJAjj2zBB8twvBPjTJidWq7MxRbtpdjZuhVX9Dlt+bXZv9qWBD6AZPv/2GgdB04t89/1O/w1cDnyilFU=";
 
$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);
 
$strUrl = "https://api.line.me/v2/bot/message/reply";
 
$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";


if($arrJson['events'][0]['message']['text'] == "สวัสดี"){
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "สวัสดีครับเจ้านาย";
}else if($arrJson['events'][0]['message']['text'] == "ชื่ออะไร"){
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "Barret Bot ครับเจ้านาย";
}else if($arrJson['events'][0]['message']['text'] == "ทำอะไรได้บ้าง"){
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "ฉันทำอะไรไม่ได้เลย กำลังพัฒนาอยู่จ้า";
}else if($arrJson['events'][0]['message']['text'] == "ID ของเรา"){
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "ID คุณคือ ".$arrJson['events'][0]['source']['userId'];
}

//ตารางเรียน เพิ่มตรงนี้ 
else if($arrJson['events'][0]['message']['text'] == "วันจันทร์เรียนอะไรบ้าง")
{
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "ตอนบ่าย4 โมงเย็น เรียนสถิติเบื้องต้น ที่ตึกวิทย ชั้น9 ห้องST1901";
}

else if($arrJson['events'][0]['message']['text'] == "วันอังคารเรียนอะไรบ้าง")
{
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "ตอนเช้า 9 โมง เรียนจิตวิทยาการศึกษาและการแนะแนว ที่คณะ ชั้น4 ห้องคอบ1409";
}

else if($arrJson['events'][0]['message']['text'] == "วันพุธเรียนอะไรบ้าง")
{
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "ตอนบ่ายโมง เรียนการวิเคราะห์และออกแบบระบบ ที่คณะ ชั้น6 ห้องคอบ1613";
}

else if($arrJson['events'][0]['message']['text'] == "วันพฤหัสเรียนอะไรบ้าง")
{
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "ว่าง วู้ฮู้วว ไปนอน 555+";
}

else if($arrJson['events'][0]['message']['text'] == "วันศุกร์เรียนอะไรบ้าง")
{
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "ตอนเช้า 9 โมง เรียนคอมพิวเตอร์เพื่อการศึกษาและการฝึกอบรม ที่คณะ ชั้น6 ห้องคอบ1616";
}

//ตารางเรียน สุดตรงนี้

else{
  $arrPostData = array();
  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  $arrPostData['messages'][0]['type'] = "text";
  $arrPostData['messages'][0]['text'] = "ยังไม่สามารถเรียนรู้คำนี้";
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_URL,$strUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close ($ch);
echo $result . "\r\n";
?>
