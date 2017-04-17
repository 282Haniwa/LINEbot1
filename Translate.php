<?php
require_once('./CurlRequest.php');
class Translate
{
    function __construct($subscriptionKey)
    {
        $this->subscriptionKey = $subscriptionKey;
    }

    private function get_token()
    {
        $uri = 'https://api.cognitive.microsoft.com/sts/v1.0/issueToken';
        $headers = array(
                //curl headers
            'Content-Type: application/json',
            'Accept: application/jwt',
            'Ocp-Apim-Subscription-Key: ' . $this->subscriptionKey,
        );
        $opt = array(
            //curl options
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
        );

        $client = new CurlRequest($uri);
        $token = 'Bearer' . $client->request('', $opt);
        return $token;
    }

    public function translate($text, $to)
    {
        $uri = 'https://api.microsofttranslator.com/v1/http.svc/Translate';

        $param = array(
            'appid' => $this->get_token(),
            'to' => $to,
            'text' => $text,
        );

        $opt = array(
            //curl options
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE => true,
            CURLOPT_GET => true,
        );

        $client = new CurlRequest($uri);
        $result = $client->request($param, $opt);
        return $result;
    }
}

 ?>
