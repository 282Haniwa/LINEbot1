<?php
require_once('./CurlRequest.php');

class ComputerVisionApi
{
    function __construct ($subscriptionKey, $image)
    {
        $this->subscriptionKey = $subscriptionKey;
        $this->image = $image;
    }
    private $uri = 'https://westus.api.cognitive.microsoft.com/vision/v1.0/analyze';
    private $parameters = array(
        // Request parameters
        'visualFeatures' => 'Categories,Description,Faces,ImageType',
        'details' => 'Celebrities',
        'language' => 'en',
    );

    private function headers()
    {
        $headers = array(
            // Request headers
            'Content-Type: application/octet-stream',
            'Host: westus.api.cognitive.microsoft.com',
            'Ocp-Apim-Subscription-Key: ' . $this->subscriptionKey,
        );
        return $headers;
    }

    private function binaryBody()
    {
        $body = $this->image;
        return $body;
    }

    private function urlBody()
    {
        $body = "{'url':'{$this->image}'}";
    }

    private function option()
    {
        $opt = array(
            //curl options
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => self::headers(),
            CURLOPT_POSTFIELDS => self::binaryBody(),
        );
        return $opt;
    }

    public function request ()
    {
        $client = new CurlRequest($this->uri);
        $result = $client->request($this->parameters, self::option());
        $array_data = json_decode($result, true);
        return $array_data;
    }

    public function get ($key)
    {
        $data = new ArrayData(self::request());
        return $data->get_value($key);
    }

}

?>
