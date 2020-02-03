<?php
namespace App;

use App\Exceptions\ValidationException;

abstract class Validate {

    /**
     * Will make sure that all the required keys are set in the array 
     * 
     * @return Boolean
     */
    public static function areSet( Array $array, Array $keys ){
        foreach( $keys as $key ){
            if( !isset( $array[ $key ] ) )
                throw new ValidationException( "$key is required", 400 );
        }
        return true;
    }

    public static function isInteger( Array $array, String $key ){
        if( !filter_var( $array[ $key ], FILTER_VALIDATE_INT ) )
            throw new ValidationException( "Invalid Type for $key", 400 );
        return true;
    }

    public static function isDate( Array $array, String $key ){
        if( !strtotime( $array[ $key ] ) )
            throw new ValidationException( "Invalid Type for $key", 400 );
        return true;
    }

    public static function isString( Array $array, String $key ){
        if( !is_string( $array[ $key ] ) )
            throw new ValidationException( "Invalid Type for $key", 400 );
        return true;
    }

    public static function isStringNotEmpty( Array $array, String $key ){
        static::isString( $array, $key );
        if ( strlen( $array[ $key ] ) == 0 )
            throw new ValidationException( "$key cannot be empty", 400 );
        return true;
    }

    public static function isEmail( Array $array, String $key ){
        $array[ $key ] = filter_var( $array[ $key ], FILTER_SANITIZE_EMAIL );
        if( !filter_var( $array[ $key ], FILTER_VALIDATE_EMAIL ) ) 
            throw new ValidationException( "$key is not a valid email address", 400 );
        return true;
    }

    public static function isBoolean( Array $array, String $key ){
        if( filter_var( $array[ $key ], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) === null ) 
            throw new ValidationException( "$key is not a valid", 400 );
        return true;
    }


}