<?php

namespace App;

class Database {
    
    private static $config = null;
    private static $connection = null;

    /**
     * Load database configuration.
     * 
     * @return void
     */
    private static function getConfiguration(){
        if( self::$config == null ){
            self::$config = json_decode( 
                file_get_contents( 
                    dirname( __DIR__ ) . '/config/database.json' 
                ),
                true 
            );
        }
    }

    /**
     * Establish the connection to the database
     * 
     * @return void
     */
    private static function connect(){
        if( self::$connection == null ){
            self::getConfiguration();
            self::$connection = new \mysqli(
                self::$config["host"], 
                self::$config["username"], 
                self::$config["password"], 
                self::$config["database"],
                self::$config["port"]
            );
            // Check connection
            if( self::$connection->connect_error ) {
                throw new \ErrorException( "Connection failed: " . self::$connection->connect_error );
            }
        }
    }

    /**
     * Disconnects the connection to the database and set the variable to null
     * 
     * @return void
     */
    public static function disconnect(){
        if( self::$connection != null ){
            self::$connection->close();
            self::$connection = null;
        }
    }

    /**
     * Directly Execute a query.  
     * 
     * @param String $sql  The SQL statement we want to query
     *
     * @return array
     */
    public static function execute( String $sql ){
        $outcome = [
            'records' => [],
            'insertId' => 0
        ];

        self::connect();
        if( !$result = self::$connection->query( $sql ) )
            throw new \ErrorException( "Query Error: " . self::$connection->error, 500 );

        if( !is_bool( $result ) )
        //if ($result->num_rows > 0) 
            $outcome['records'] = $result->fetch_all( \MYSQLI_ASSOC );
        
        $outcome['insertId'] = self::$connection->insert_id;

        return $outcome;
    } 

    /**
     * Query using Binding. 
     * 
     * @param string $sql  The SQL statement we want to query
     * @param string $type  The type of binding to use per field
     * @param array $values  An array with the referenced values we wanna bind
     *
     * @return array
     */
    public static function bindAndQuery( String $sql, String $type = '', Array $values = [] ){
        $outcome = [
            'records' => [],
            'insertId' => 0
        ];

        self::connect();
        if( !( $stmt = self::$connection->prepare( $sql ) ) )
            throw new \ErrorException( "Statement Error: " . self::$connection->error, 500 );

        // adding the binding type as the first item of the array
        if( $values != [] ){
            array_unshift( $values, $type );
            if( !call_user_func_array( array( $stmt, "bind_param" ), $values ) )
                throw new \ErrorException( "Binding Error: " . $stmt->error, 500 );
        }

        if( !$stmt->execute() )
            throw new \ErrorException( "Execute Error: " . $stmt->error, 500 );
        
        $stmt_result = $stmt->get_result();

        if( !is_bool( $stmt_result ) )
            $outcome['records'] = $stmt_result->fetch_all( \MYSQLI_ASSOC );    

        $outcome['insertId'] = $stmt->insert_id;

        return $outcome;
    } 

}
