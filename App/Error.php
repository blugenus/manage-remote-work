<?php

namespace App;

class Error extends Controller{

    /**
     * Error handler. Handles errors by throwing an ErrorException.
     *
     * @param int $errno  Error level
     * @param string $errstr  Error message
     * @param string $errfile  Filename the error was raised in
     * @param int $errline  Line number in the file
     *
     * @return void
     */
    public function errorHandler( Int $errno, String $errstr, String $errfile, Int $errline ) {
        if ( error_reporting() !== 0 ) {  // to keep the @ operator working
            throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
        }
    }

    /**
     * Exception handler. Outputs an html page reporting the issue.
     *
     * @param $exception  The exception details
     *
     * @return void
     */
//    public function exceptionHandler( \ErrorException $exception ){
    public function exceptionHandler( $exception ){
        $data = [
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ];
        $this->response->statusCode( $exception->getCode() );
        $this->response->content( $data );
        $this->response->render();
    }


}