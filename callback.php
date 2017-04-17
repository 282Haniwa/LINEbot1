<?php
/**
* Copyright 2016 LINE Corporation
*
* LINE Corporation licenses this file to you under the Apache License,
* version 2.0 (the "License"); you may not use this file except in compliance
* with the License. You may obtain a copy of the License at:
*
*   https://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
* WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
* License for the specific language governing permissions and limitations
* under the License.
*/

require_once('./LINEBotTiny.php');
require_once('./ComputerVisionApi.php');
require_once('./Translate.php');

const LINE_CHANNEL_ACCESS_TOKEN = "your LINE channel acces token";  //LINE's Channel Access Token
const LINE_CHANNEL_SECRET = "your LINE secret";  //LINE's Channel Secret
const MS_COMPUTER_VISION_KEY = "your microsoft computer vision api's Subscription key";  //Computer Vision api's Subscription-Key
const MS_TRANSLATOR_KEY = "your microsoft translator Subscription-key";  //Microsoft Translator's Subscription-Key


$client = new LINEBotTiny(LINE_CHANNEL_ACCESS_TOKEN, LINE_CHANNEL_SECRET);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
        $message = $event['message'];
        switch ($message['type']) {
            case 'text':
            if (preg_match('/^@haniwa+[ 　].+$/i', $message['text'])) {
                $translate = new Translate(MS_TRANSLATOR_KEY);
                $text = str_replace('@haniwa', '', $message['text']);
                $text = preg_replace('/^[ 　]+/u', '', $text);
                mb_regex_encoding("UTF-8");
                if (preg_match('/^.+[ぁ-んァ-ヶー一-龠].+$/u', $text)) {
                    //日本語が含まれている時
                    $result = $translate->translate($text, 'en');
                } else {
                    if (preg_match('/^[!-@[-`{-~0-9\s\t\n]+$/', $text)) {
                        //翻訳不可能な文字列の時
                        $result = '翻訳可能な文字列を入力してください。';
                    } else {
                        //日本語じゃなくて記号だけの文字列じゃない時
                        $result = $translate->translate($text, 'ja');
                    }
                }
                $client->replyMessage(text_message($event['replyToken'], $result));
                break;
            } else {
                $client->replyMessage(text_message($event['replyToken'], "'@haniwa 'の後に翻訳可能な文字列を入力してください。"));
            }
            break;

            case 'image':
            $image_binary = $client->get_content($event['message']['id']);

            $cvapi = new ComputerVisionApi(MS_COMPUTER_VISION_KEY, $image_binary);
            $cvdata = $cvapi->request();
            $caption = $cvdata['description']['captions'][0]['text'];
            if($cvdata['faces'][0]['age'])
                $age = "\n" . $cvdata['faces'][0]['age'] . "歳";

            $translate = new Translate(MS_TRANSLATOR_KEY);
            $result = $translate->translate($caption, 'ja');

            $client->replyMessage(text_message($event['replyToken'], $caption . "\n" . $result . $age));
            break;


            default:
            error_log("Unsupporeted message type: " . $message['type']);
            break;
        }
        break;
        default:
        error_log("Unsupporeted event type: " . $event['type']);
        break;
    }
};

function text_message ($replyToken, $text)
{
    $array = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
    return $array;
}


?>
