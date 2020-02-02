<?php
namespace App\Models;

class User extends \App\Model {

    /**
     * create a new user.
     *
     * @param String $username  The username of the new user.
     * @param String $name  The name of the new user.
     * @param String $email  The email address of the new user.
     * @param Boolean $isAdmin  if the new user is an admistrator or not
     * 
     * @return Array
     */
    public static function create( String $username, String $name, String $email, Int $isAdmin ){
        $password = static::getRandomPassword();
        $passwordHash = static::getPasswordHash( $password );
        $result = static::bindAndQuery( 
            "INSERT INTO `users` ( `username`, `password`, `name`, `email`, `isAdmin` ) VALUES ( ?, ?, ?, ?, ? );",
            "ssssi",
            [ &$username, &$passwordHash, &$name, &$email, &$isAdmin ]
        );
        static::sendNewUserEmail( $email, $name, $username, $password );
        return [
            'insertId' => $result['insertId']
        ];
    }

    /**
     * returns the details of all users
     * 
     * @return Array
     */
    public static function select(){
        $result = static::bindAndQuery( "SELECT `userId`, `username`, `name`, `email`, `isAdmin`, `isEnabled` FROM `users`" );
        return $result['records'];
    }

    /**
     * returns the details of a selected user
     *
     * @param Int $userId  The userId of whom user details to return
     * 
     * @return void
     */
    public static function selectById( $userId ){
        $result = static::bindAndQuery( 
            "SELECT `userId`, `username`, `name`, `email`, `isAdmin`, `isEnabled` FROM `users` where `userId` = ?;",
            'i',
            [ &$userId ]
        );
        return $result['records'];
    }

    /**
     * sending the email template for the declined request 
     *
     * @param Int $userId  The userId of user to update
     * @param String $username  The updated username of the logged in user
     * @param String $name  The updated name of the user .
     * @param String $email  the updated email of the user. 
     * @param Int $isAdmin  whether the user is an administrator or not.
     * @param Int $isEnabled  whether the user account is enabled or not. 
     * 
     * @return Boolean
     */
    public static function update( Int $userId, String $username, String $name, String $email, Int $isAdmin, Int $isEnabled ){
        $result = static::bindAndQuery( 
            "UPDATE `users` SET 
                `username` = ?, 
                `name` = ?, 
                `email` = ?, 
                `isAdmin` = ?,
                `isEnabled` = ?
            WHERE userId = ?;",
            "sssiii",
            [ &$username, &$name, &$email, &$isAdmin, &$isEnabled, &$userId ]
        );
        return true;
    }

    /**
     * return a randomly generated password
     * 
     * @return String
     */
    public static function getRandomPassword(){
        return \App\Models\Auth::getRandomPassword();
    }

    /**
     * return a hash of the password.
     *
     * @param String $password  The selected password
     * 
     * @return String
     */
    public static function getPasswordHash( String $password ){
        return \App\Models\Auth::getPasswordHash( $password );
    }

    /**
     * sending the email template for the new user
     *
     * could have user twig here... 
     *
     * @param String $email  The email of the newly created user
     * @param String $name  The name of the newly created user
     * @param String $username  the username of the newly created user. 
     * @param String $password  the password of of the newly created user.
     * 
     * @return void
     */
    public static function sendNewUserEmail( String $email, String $name, String $username, String $password ){
        $body = "
        <p>Dear $name</p>
        <p>Your account has been created.</p>
        <p>Your username is <b>$username</b>.</p>
        <p>Your password is <b>$password</b>.</p>
        <p><a href=\"http://" . $_SERVER['HTTP_HOST'] . "/\">Click here</a> to access the system.</p>
        <p>The management</p>
        ";
        \App\Smtp::send( 
            $email, 
            'Your account has been created', 
            $body
        );
    }

}