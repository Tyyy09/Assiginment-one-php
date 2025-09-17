<?php
class CatApi {
    private $baseUrl;
    private $apiKey;
    //public an array that will hold all the cat breeds fetched from the API.
    public $breeds = [];

    public function __construct($baseUrl, $apiKey){
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * Fetch all breeds from The Cat API
     */
    public function fetchBreeds() {
        //Initializes a cURL session to the URL stored in $baseUrl
        $ch = curl_init($this->baseUrl);
        //Tell cURL to return the response as a string instead of printing it.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Add an HTTP header x-api-key with my API key for authentication.
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-api-key: ' . $this->apiKey
        ]);
        //Ensures SSL are verified
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        //check error if cURl encounterd any error, if yes it throw an exeption with the error message.
        $response = curl_exec($ch);

        if(curl_errno($ch)){
            throw new Exception("cURL Error: " . curl_error($ch));
        }

        //Gets the HTTP status code of the response like 200, 404
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        //If not 200
        if($statusCode != 200){
            throw new Exception("API returned status code: $statusCode");
        }
        //Decodes the JSON response
        $data = json_decode($response, true);

        // Keep only breeds that have images
        $this->breeds = array_values(array_filter($data, fn($b) => isset($b['image']['url'])));
    }

    /**
     * Get index 0,1,2 and return if it exists
     * if the index doesnt exist, it returns null
     */
    public function getBreed($index) {
        return $this->breeds[$index] ?? null;
    }
}
