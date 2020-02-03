<?php

use PHPUnit\Framework\TestCase;

final class HttpRequestTest extends TestCase {

    public function testHttpRequest(){
        $mockedRequest = $this->getMockBuilder( 'App\HttpRequest' )
                              ->disableOriginalConstructor()
                              ->setMethods( [ 'getRawBody', 'getServerVariables' ] )
                              ->getMock();

        $mockedRequest->method('getRawBody')
                      ->will($this->returnValue('{"sample":50}'));

        $mockedRequest->method('getServerVariables')
                      ->will($this->returnValue([ 
                            'REQUEST_URI' => 'http://127.0.0.1/api/users/14/licenses?123',
                            'REQUEST_METHOD' => 'GET'
                        ]));

        $mockedRequest->__construct();

        $this->assertEquals( 'GET', $mockedRequest->getMethod() );
        $this->assertEquals( 'http://127.0.0.1/api/users/14/licenses', $mockedRequest->getUri() );
        $this->assertEquals( 50, $mockedRequest->getPostData()['sample'] );
    }

    public function testHttpResponse(){
        $mockedResponse = $this->getMockBuilder( '\App\HttpResponse' )
                               ->setMethods(['render'])
                               ->getMock();

        $mockedResponse->statusCode( 50 );
        $mockedResponse->header( 'headerItem2Index1' );
        $mockedResponse->content( [ 'key1' => 'value1' ] );

        $this->assertEquals( 50, $mockedResponse->statusCode() );
        $this->assertEquals( 'Content-Type: application/json', $mockedResponse->header()[0] );
        $this->assertEquals( 'headerItem2Index1', $mockedResponse->header()[1] );
        $this->assertEquals( '{"key1":"value1"}', $mockedResponse->content() );
    }

}