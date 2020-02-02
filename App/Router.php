<?php

namespace App;

/**
 * Router Class - handles the application's routes
 */
class Router {

    /**
     * Run the router.
     *
     * for more detailed information on the router itself please visit
     * https://github.com/nikic/FastRoute
     * 
     * @return void
     */
    public static function execute( \FastRoute\Dispatcher\GroupCountBased &$dispatcher, HttpRequest &$request, HttpResponse &$response ){
        $routeInfo = $dispatcher->dispatch( $request->getMethod(), $request->getUri() );
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                throw new \ErrorException('No route matched.', 404);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                throw new \ErrorException('Route matched but method not allowed', 405);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                if( $handler['auth'] && !\App\Security::isUserLoggedIn() ){
                    throw new \ErrorException('Unauthorized', 401);
                }
                if( $handler['mustBeAdmin'] && !\App\Security::isUserAdmin() ){
                    throw new \ErrorException('Unauthorized', 401);
                }
                $controller = 'App\Controllers\\' . $handler['controller'];
                $function = $handler['function'];
                ( new $controller( $request, $response ) )->$function( $vars );

                break;
        }
    }

    /**
     * Run the router.
     *
     * @param String $controller  The class of the controller to initialise.
     * @param String $method  The method of the initialsed controller to call.
     * @param Boolean $auth  if the user must be logged in in order to access this route
     * @param Boolean $admin  if the user must has admin rights in order to access this route
     * 
     * @return void
     */
    public static function setRouteHandler( $controller, $method, $auth = true, $admin = false ){
        return [ 
            'controller' => $controller, 
            'function' => $method, 
            'auth' => $auth, 
            'mustBeAdmin' => $admin 
        ];
    }

}