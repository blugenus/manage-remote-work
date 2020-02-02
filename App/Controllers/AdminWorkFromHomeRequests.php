<?php
namespace App\Controllers;

use App\Models\WorkFromHome;
Use App\Validate;

/**
 * Administrators Work from Home Reqest reject/approve controller
 */
class AdminWorkFromHomeRequests extends \App\Controller {

    /**
     * List the work from home requests from all users
     *
     * @return void
     */
    public function listAll(){
        $this->response->content([
            'success' => true,
            'records' => WorkFromHome::selectAll()
        ]);
    }
    
    /**
     * reject a work from home request
     *
     * @return void
     */
    public function decline( $array ){
        // make sure required fields are set
        Validate::areSet( $this->request->getPostData(), [ 'comment' ] );
        // validate contents of fields.
        Validate::isString( $this->request->getPostData(), 'comment' );

        WorkFromHome::declineRequest( $array['requestId'], $this->request->getPostData()['comment'] );
        $this->response->content([
            'success' => true
        ]);
    }

    /**
     * approve a work from home request.
     *
     * @return void
     */
    public function approve( $array ){
        // make sure required fields are set
        Validate::areSet( $this->request->getPostData(), [ 'comment' ] );
        // validate contents of fields.
        Validate::isString( $this->request->getPostData(), 'comment' );

        WorkFromHome::approveRequest( $array['requestId'], $this->request->getPostData()['comment'] );
        $this->response->content([
            'success' => true
        ]);
    }    

}