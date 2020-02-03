<?php

use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase {

    public function testExecute(){
        $result = \App\Database::execute( 'SELECT count(1) `coun` FROM USERS;' );
        // since we are counting there should always be only 1 records
        $this->assertEquals( 1, sizeof( $result['records'] ) );
        // the result cannot be less then 0 records;
        $this->assertGreaterThanOrEqual( 0, $result['records'][0]['coun'] );
    }

    public function testBindAndQuery(){
        $var1 = 111;
        $var2 = 222.333;
        $var3 = 'test444';
        $result = \App\Database::bindAndQuery( 
            'SELECT ? `var1`, ? `var2`, ? `var3`;',
            'ids',
            [
                &$var1,
                &$var2,
                &$var3
            ]
        );
        // there should always be only 1 records
        $this->assertEquals( 1, sizeof( $result['records'] ) );
        // the result cannot be less then 0 records;
        $this->assertEquals( 111, $result['records'][0]['var1'] );
        $this->assertEquals( 222.333, $result['records'][0]['var2'] );
        $this->assertEquals( 'test444', $result['records'][0]['var3'] );
    }

}