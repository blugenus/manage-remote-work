<?php
namespace App\Controllers;

use App\Models\System;

/**
 * Setup controller - handles the database setup 
 */
class Setup extends \App\Controller {

    /**
     * setups up database and populates it with the default information.
     * 
     * @return void
     */
    public function system(){
        System::setup();
        $this->response->content([
            'success' => true
        ]);
        
    }

}