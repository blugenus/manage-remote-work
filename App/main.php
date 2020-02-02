<?php
/**
 * some initial configuaration
 */
ignore_user_abort(false); // aborts if the user connection is closed/aborted.
set_time_limit(30); // limit the maximum execution time.
date_default_timezone_set('UTC'); // ensuring the php time is UTC.
require dirname(__DIR__) . '/vendor/autoload.php'; // adding composer autoloader

// ensuring that the database is closed at the end :)
register_shutdown_function( function(){
    \App\Database::disconnect();
});

// Initialise request and response objects
$request = new App\HttpRequest();
$response = new App\HttpResponse();

// setting up Error and Exception Handling
error_reporting(E_ALL);
$error = new App\Error( $request, $response );
set_error_handler( [ $error, 'errorHandler' ] );
set_exception_handler( [ $error, 'exceptionHandler' ] );

\App\Security::initialise(); // preparing session

// Load router's Routes - we can use cache option
$dispatcher = \FastRoute\simpleDispatcher( function( \FastRoute\RouteCollector $r ) {
    $r->addGroup( '/api', function ( \FastRoute\RouteCollector $r ) {
        // setup the system ... should be remarks aftrer running it. 
        $r->addRoute( 'GET', '/setup', App\Router::setRouteHandler( 'Setup', 'system', false ) );
        // login and logout function
        $r->addRoute( 'POST', '/login', App\Router::setRouteHandler( 'Login', 'userlogIn', false ) );
        $r->addRoute( 'GET', '/logout', App\Router::setRouteHandler( 'Login', 'userLogOut', false ) );
        $r->addRoute( 'GET', '/current-user', App\Router::setRouteHandler( 'Login', 'currentUser', false ) );
        // Users
        $r->addGroup( '/users', function ( \FastRoute\RouteCollector $r ) {
            $r->addRoute( 'GET', '', App\Router::setRouteHandler( 'Users', 'listAll', true, true ) );
            $r->addRoute( 'POST', '', App\Router::setRouteHandler( 'Users', 'create', true, true ) );

            $r->addGroup( '/{userId:\d+}', function ( \FastRoute\RouteCollector $r ) {
                $r->addRoute( 'PUT', '', App\Router::setRouteHandler( 'Users', 'update', true, true ) );
                // Users licenses
                $r->addGroup( '/licenses', function ( \FastRoute\RouteCollector $r ) {
                    $r->addRoute( 'GET', '', App\Router::setRouteHandler( 'UserLicenses', 'listAll', true, true ) );
                    $r->addRoute( 'PUT', '', App\Router::setRouteHandler( 'UserLicenses', 'update', true, true ) );
                });
            });
            // Users Work From Home
            $r->addGroup( '/work-from-home', function ( \FastRoute\RouteCollector $r ) {
                $r->addRoute( 'GET', '', App\Router::setRouteHandler( 'AdminWorkFromHomeRequests', 'listAll', true, true ) );
                $r->addGroup( '/{requestId:\d+}', function ( \FastRoute\RouteCollector $r ) {
                    $r->addRoute( 'PATCH', '/decline', App\Router::setRouteHandler( 'AdminWorkFromHomeRequests', 'decline', true, true ) );
                    $r->addRoute( 'PATCH', '/approve', App\Router::setRouteHandler( 'AdminWorkFromHomeRequests', 'approve', true, true ) );
                });                    
            });
        });

        $r->addGroup( '/user', function ( \FastRoute\RouteCollector $r ) {
            $r->addGroup( '/work-from-home', function ( \FastRoute\RouteCollector $r ) {
                $r->addRoute( 'GET', '', App\Router::setRouteHandler( 'UserWorkFromHomeRequests', 'listAll' ) );
                $r->addRoute( 'POST', '', App\Router::setRouteHandler( 'UserWorkFromHomeRequests', 'create' ) );
                $r->addGroup( '/{requestId:\d+}', function ( \FastRoute\RouteCollector $r ) {
                    $r->addRoute( 'PATCH', '/cancel', App\Router::setRouteHandler( 'UserWorkFromHomeRequests', 'cancel' ) );
                }); 
            });
        });

    });
});

// execute the route.
try {
    App\Router::execute( $dispatcher, $request, $response );
} catch( App\Exceptions\ValidationException $exception ){
    $data = [
        'success' => false,
        'message' => $exception->getMessage()
    ];
    $response->statusCode( $exception->getCode() );
    $response->content( $data );
}

$response->render();


