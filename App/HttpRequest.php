<?php

namespace App;

class HttpRequest {

    private $uri;
    private $postData; 

    /**
     * Http Request Contructor
     *
     * @return void
     */
    public function __construct() {
        $this->processUri();
        $this->processRequestBody();
    }

    /**
     * return the method for the request
     *
     * @return String
     */
    public function getMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * return the uri for the request
     *
     * @return String
     */
    public function getUri(){
        return $this->uri;
    }

    /**
     * return the querystring array for the request
     *
     * @return Array
     */
    public function getQueryString(){
        return $_GET;
    }

    /**
     * return the post data (in JSON) array by reference for the request
     *
     * @return Array
     */
    public function &getPostData(){
        return $this->postData;
    }

    /**
     * removes the querystring from the uri in the url.
     *
     * @return void
     */
    private function processUri(){
        $uri = $_SERVER['REQUEST_URI'];
        $uri = explode( '?', $uri )[0];
        $this->uri = rawurldecode($uri);    
    }

    /**
     * reads the body of the requests and tries to parse it as JSON into an array.
     *
     * @return void
     */
    private function processRequestBody(){
        $tmp = file_get_contents("php://input");
        $data = json_decode( $tmp, true );
        if( $data == null ){
            $data = [];  
        } 
        $this->postData = $data;
    }

}