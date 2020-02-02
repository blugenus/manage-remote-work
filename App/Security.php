<?php
namespace App;

class Security {

    /**
     * Initialise the session and if needed set it to default.
     *
     * @return void
     */
    public static function initialise(){
        session_start(); 
        if( !isset( $_SESSION['loggedin'] ) ){
            static::resetSession();
        }
    }

    /**
     * Configure the user session
     *
     * @param Boolean $loggedin  if the user is logged in
     * @param Int $userId  The userId of the logged in user
     * @param String $username  The username of the logged in user
     * @param Int $isAdmin  if the user has admin rights .. 1 = true, 0 = false
     * 
     * @return void
     */
    public static function setSession( $loggedin = false, $userId = 0, $username = '', $isAdmin = 0 ){
        $_SESSION['loggedin'] = $loggedin; 
        $_SESSION['userId'] = $userId; 
        $_SESSION['username'] = $username;        
        $_SESSION['isAdmin'] = $isAdmin; 
    }

    /**
     * reset the current session. 
     *
     * @return void
     */
    public static function resetSession(){
        static::setSession(); 
    }

    /**
     * returns whether the user is currently logged in or not.
     *
     * @return Boolean
     */
    public static function isUserLoggedIn(){
        return $_SESSION['loggedin'] == 1;
    }

    /**
     * returns whether the currently logged user is an Admin or not
     *
     * @return Boolean
     */
    public static function isUserAdmin(){
        return $_SESSION['isAdmin'] == 1;
    }

    /**
     * returns the currently logged in username
     *
     * @return String
     */
    public static function isUsername(){
        return $_SESSION['username'];
    }

    /**
     * returns the currently logged in userId
     *
     * @return Int
     */
    public static function getCurrentUserId(){
        return $_SESSION['userId'];
    }


}