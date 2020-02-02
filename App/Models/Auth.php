<?php
namespace App\Models;

class Auth extends \App\Model {

    /**
     * Verifies username and password; if succesful they update the session variables
     * 
     * @param String $user  The user name the user poster
     * @param String $password  The password the user posted
     *
     * @return Boolean
     */
    public static function login( String $username, String $password ){
        if( $user = static::verifyLoginDetails( $username, $password ) ){
            // set the session vaiables
            \App\Security::setSession( true, $user['userId'], $user['username'], $user['isAdmin'] ); 
            return $user;
        }
        static::logout();
        return false;
    }

    /**
     * Logout current user
     * 
     * @return Boolean
     */
    public static function logout(){
        \App\Security::resetSession();
        return true;
    }

    /**
     * Verify username and password
     * 
     * @param String $user  The user name the user poster
     * @param String $password  The password the user posted
     *
     * @return Boolean
     */
    public static function verifyLoginDetails( String $username, String $password ){
        $result = static::bindAndQuery( 
            'SELECT `userId`, `username`, `isAdmin`, `password` FROM `users` WHERE `username` = ? and `isEnabled` = 1;', 
            's', 
            [ &$username ] 
        );
        // if the username is in our database
        if( sizeof( $result['records'] ) == 1 ){
            if( password_verify( $password, $result['records'][0]['password'] ) ){
                unset( $result['records'][0]['password'] );
                return $result['records'][0];
            }
        } 
        return false;
    }

    /**
     * return the hash of the specified password
     * 
     * @param String $password  The password to be hashed
     *
     * @return String
     */
    public static function getPasswordHash( String $password ){
        return password_hash( $password, PASSWORD_BCRYPT, ['cost' => 12] );
    }

    /**
     * return a randomly generated password af a specified length
     * 
     * @param Int $length  The user name the user poster
     *
     * @return String
     */
    public static function getRandomPassword( Int $length = 8 ){
        $allowedCharacters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $noOfCharacters = strlen($allowedCharacters) - 1;
        $randomPassword = [];
        for( $coun=0; $coun<$length; $coun++ ){
            $randomPassword[] = $allowedCharacters[ rand( 0, $noOfCharacters ) ];
        }
        return implode( '', $randomPassword );
    }

}