<?php
namespace App\Controllers;

use App\Models\Auth;
Use App\Validate;

/**
 * Login controller - handles authentifiacion requests
 */
class Login extends \App\Controller {

    /**
     * return the information pertinent to the currently logged in user
     * 
     * @return void
     */
    public function currentUser(){
        if( \App\Security::isUserLoggedIn() ){
            $this->response->content([
                'success' => \App\Security::isUserLoggedIn(),
                'user' => [
                    "userId"=> \App\Security::getCurrentUserId(),
                    "username"=> \App\Security::isUsername(),
                    "isAdmin"=> \App\Security::isUserAdmin()
                ]
            ]);
        } else {
            $this->response->content([
                'success' => false
            ]);

        }
    }

    /**
     * Try to login using the provided username and password
     * 
     * @return void
     */
    public function userlogIn(){ // process provided account
        // make sure required fields are set
        Validate::areSet( $this->request->getPostData(), [ 'username', 'password' ] );
        // validate contents of fields.
        Validate::isStringNotEmpty( $this->request->getPostData(), 'username' );
        Validate::isStringNotEmpty( $this->request->getPostData(), 'password' );
        
        if( $user = Auth::login( 
            $this->request->getPostData()['username'],
            $this->request->getPostData()['password']
        )) {
            $this->response->content([
                'success' => true,
                'user' => $user
            ]);
        } else {
            $this->response->content([
                'success' => false
            ]);
        }
    }

    /**
     * Logs out the user 
     *
     * @return void
     */
    public function userLogOut(){
        Auth::logout();
        $this->response->content([
            'success' => true
        ]);
    }

}