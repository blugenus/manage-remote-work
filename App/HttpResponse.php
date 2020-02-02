<?php

namespace App;

class HttpResponse {

    private $statusCode = 200;
    private $content = null;
    private $headers = [
        'Content-Type: application/json'
    ];

    /**
     * Set/Get the response status code
     *
     * @param Int $newStatusCode  new status code 
     *
     * @return Int
     */
    public function statusCode( Int $newStatusCode = 0 ){
        if( $newStatusCode != 0 ){
            $this->statusCode = $newStatusCode;
        }
        return $this->statusCode;
    }

    /**
     * Add a new header/Get teh array of headers
     *
     * @param String $newHeader  new Header to add to the array (if not '')
     *
     * @return Array
     */
    public function header( String $newHeader = '' ){
        if( $newHeader != '' ){
            $this->headers[] = $newHeader;
        }
        return $this->headers;
    }

    /**
     * Set/Get the content of the response.
     *
     * @param Array $newContent  new Content the send to the browser
     *
     * @return Array
     */
    public function content( Array $newContent = null ){
        if( $newContent != null ){
            $this->content = json_encode( $newContent );
        }
        return $this->content;
    }    

    /**
     * Send to response to the browser
     *
     * @return void
     */
    public function render() {
        http_response_code( $this->statusCode );
        foreach( $this->headers as $header ){
            header( $header, true );
        }
        echo $this->content; 
    }

}