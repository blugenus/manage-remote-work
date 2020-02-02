<?php
namespace App\Controllers;

use App\Models\License;
Use App\Validate;

/**
 * User Licenses controller - handles licenses requests
 */
class UserLicenses extends \App\Controller {

    /**
     * sends to the browser all the licenses information for the selected user.
     *
     * @return void
     */
    public function listAll( $array ){
        $this->response->content([
            'success' => true,
            'records' => License::getUserLicenses( $array['userId'] )
        ]);
    }

    /**
     * update the licensing information for a user.
     *
     * @return void
     */
    public function update( $array ){
        // TO DO
        // Should implement validation for $this->request->getPostData(). 
        //
        License::setUserLicenses( $array['userId'], $this->request->getPostData() );
        $this->response->content([
            'success' => true
        ]);
    }



}