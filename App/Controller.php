<?php

namespace App;

/*
 * Base Controller
 */
abstract class Controller {

    protected $request;
    protected $response;

    /**
     * Controller Contructor
     *
     * @param HttpRequest $request  Request Object (by reference)
     * @param HttpResponse $response  Response Object (by reference)
     * 
     * @return void
     */
    public function __construct( HttpRequest &$request, HttpResponse &$response ) {
        $this->request = &$request;
        $this->response = &$response;
    }

}