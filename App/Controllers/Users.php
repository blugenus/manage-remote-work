<?php
namespace App\Controllers;

use App\Models\User;
Use App\Validate;

/**
 * Users controller - handles requests related to the users resources
 */
class Users extends \App\Controller {

    /**
     * sends to the broswer details about all users
     *
     * @return void
     */
    public function listAll(){
        $this->response->content([
            'success' => true,
            'records' => User::select()
        ]);
    }

    /**
     * creates a new user 
     *
     * @return void
     */
    public function create(){
        // make sure required fields are set
        Validate::areSet( $this->request->getPostData(), [ 'username', 'name', 'email', 'isAdmin' ] );
        // validate contents of fields.
        Validate::isStringNotEmpty( $this->request->getPostData(), 'username' );
        Validate::isStringNotEmpty( $this->request->getPostData(), 'name' );
        Validate::isEmail( $this->request->getPostData(), 'email' );
        Validate::isBoolean( $this->request->getPostData(), 'isAdmin' );

        $result = User::create(
            $this->request->getPostData()['username'],
            $this->request->getPostData()['name'],
            $this->request->getPostData()['email'],
            $this->request->getPostData()['isAdmin']
        );
        $this->response->content([
            'success' => true,
            'userId' => $result['insertId']
        ]);
    }

    /**
     * update a user's details
     *
     * @return void
     */
    public function update( $array ){        
        // make sure required fields are set
        Validate::areSet( $this->request->getPostData(), [ 'username', 'name', 'email', 'isAdmin' ] );
        // validate contents of fields.
        Validate::isStringNotEmpty( $this->request->getPostData(), 'username' );
        Validate::isStringNotEmpty( $this->request->getPostData(), 'name' );
        Validate::isEmail( $this->request->getPostData(), 'email' );
        Validate::isBoolean( $this->request->getPostData(), 'isAdmin' );
        Validate::isBoolean( $this->request->getPostData(), 'isEnabled' );

        $this->response->content([
            'success' => User::update(
                $array['userId'],
                $this->request->getPostData()['username'],
                $this->request->getPostData()['name'],
                $this->request->getPostData()['email'],
                $this->request->getPostData()['isAdmin'],
                $this->request->getPostData()['isEnabled']
            )
        ]);
    }

}