<?php
namespace App\Controllers;

use App\Models\WorkFromHome;
Use App\Validate;

/**
 * User Work From Home Requests controller - handles the creation and cancellation of Work From Home requests
 */
class UserWorkFromHomeRequests extends \App\Controller {

    /**
     * send all the work from home requests of the logged in user
     *
     * @return void
     */
    public function listAll(){
        $this->response->content([
            'success' => true,
            'records' => WorkFromHome::selectAllForLoggedInUser()
        ]);
    }

    /**
     * The logged in user creates a new work from home request
     *
     * @return void
     */
    public function create(){
        // make sure required fields are set
        Validate::areSet( $this->request->getPostData(), [ 'date', 'hours', 'comment' ] );
        // validate contents of fields.
        Validate::isDate( $this->request->getPostData(), 'date' );
        Validate::isInteger( $this->request->getPostData(), 'hours' );
        Validate::isString( $this->request->getPostData(), 'comment' );

        $result = WorkFromHome::createForLoggedInUser( 
            $this->request->getPostData()['date'],
            $this->request->getPostData()['hours'],
            $this->request->getPostData()['comment'] 
        );
        $this->response->content([
            'success' => true,
            'insertId' => $result['insertId']
        ]);
    }    

    /**
     * The logged in user cancels on of his work from home requests
     *
     * @return void
     */
    public function cancel( $array ){
        WorkFromHome::cancelRequestForLoggedInUser( $array['requestId']);
        $this->response->content([
            'success' => true
        ]);
    }

}