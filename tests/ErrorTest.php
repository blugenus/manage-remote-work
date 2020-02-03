<?php

use PHPUnit\Framework\TestCase;

final class ErrorTest extends TestCase {

    public function testErrorHandler(){

        $mockedRequest = $this->getMockBuilder( 'App\HttpRequest' )
                              ->disableOriginalConstructor()
                              ->getMock();
        $response = new App\HttpResponse();
        $error = new App\Error( $mockedRequest, $response );

        $this->expectException( \ErrorException::class );
        $error->errorHandler( 100, 'errorString', 'errorFile', 200 );

    }

    public function testExceptionHandler(){

        $mockedRequest = $this->getMockBuilder( '\App\HttpRequest' )
                              ->disableOriginalConstructor()
                              ->getMock();

        $mockedResponse = $this->getMockBuilder( '\App\HttpResponse' )
                               ->setMethods(['render'])
                               ->getMock();

        $error = new App\Error( $mockedRequest, $mockedResponse );

        $exception = new \ErrorException( 'errorString', 500, 100, 'errorFile', 200 );
        $error->exceptionHandler( $exception );

        $this->assertEquals( 500, $mockedResponse->statusCode(0) );

        $json = json_decode( $mockedResponse->content(), true );
        $this->assertEquals( 'ErrorException', $json['class'] );
        $this->assertEquals( 'errorString', $json['message'] );
        $this->assertEquals( 'errorFile', $json['file'] );
        $this->assertEquals( '200', $json['line'] );

    }

}