<?php

use PHPUnit\Framework\TestCase;

final class ValidationTest extends TestCase{

    public function testValidEmail(){
        $this->assertTrue( \App\Validate::isEmail( ['email'=>'user@example.com'], 'email' ) );
    }

    public function testInvalidEmail(){
        $this->expectException( \App\Exceptions\ValidationException::class );
        \App\Validate::isEmail( ['email'=>'user@example'], 'email' );
    }

    public function testValidBoolean(){
        $this->assertTrue( \App\Validate::isBoolean( [ 'boolean' => true ], 'boolean' ) );
        $this->assertTrue( \App\Validate::isBoolean( [ 'boolean' => 'true' ], 'boolean' ) );
        $this->assertTrue( \App\Validate::isBoolean( [ 'boolean' => 1 ], 'boolean' ) );
        $this->assertTrue( \App\Validate::isBoolean( [ 'boolean' => false ], 'boolean' ) );
        $this->assertTrue( \App\Validate::isBoolean( [ 'boolean' => 'false' ], 'boolean' ) );
        $this->assertTrue( \App\Validate::isBoolean( [ 'boolean' => 0 ], 'boolean' ) );
    }

    public function testInvalidBoolean(){
        $this->expectException( \App\Exceptions\ValidationException::class );
        \App\Validate::isBoolean( [ 'boolean' => 'george' ], 'boolean' );
    }

    public function testValidStringNotEmpty(){
        $this->assertTrue( \App\Validate::isStringNotEmpty( [ 'string' => 'example' ], 'string' ) );
    }

    public function testInvalidStringNotEmpty(){
        $this->expectException( \App\Exceptions\ValidationException::class );
        \App\Validate::isStringNotEmpty( [ 'string' => '' ], 'string' );
    }

    public function testValidString(){
        $this->assertTrue( \App\Validate::isString( [ 'string' => ''], 'string' ) );
    }

    public function testInvalidString(){
        $this->expectException( \App\Exceptions\ValidationException::class );
        \App\Validate::isString( [ 'string' => 99 ], 'string' );
    }

    public function testValidAreSet(){
        $this->assertTrue( \App\Validate::areSet( [ 'key1' => '', 'key2' => '', 'key3' => '' ], [ 'key1', 'key2' ] ) );
    }

    public function testInvalidAreSet(){
        $this->expectException( \App\Exceptions\ValidationException::class );
        \App\Validate::areSet( [ 'key1' => '', 'key2' => '', 'key3' => '' ], [ 'key1', 'key9' ] );
    }

    public function testValidDate(){
        $this->assertTrue( \App\Validate::isDate( [ 'date' => '2020-02-01' ], 'date' ) );
    }

    public function testInvalidDate(){
        $this->expectException( \App\Exceptions\ValidationException::class );
        \App\Validate::isDate( [ 'date' => '200000000' ], 'date' );
    }

    public function testValidInteger(){
        $this->assertTrue( \App\Validate::isInteger( [ 'integer' => 99 ], 'integer' ) );
    }

    public function testInvalidInteger(){
        $this->expectException( \App\Exceptions\ValidationException::class );
        \App\Validate::isInteger( [ 'integer' => '9a' ], 'integer' );
    }


}