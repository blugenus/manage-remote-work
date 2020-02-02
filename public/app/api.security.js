(function(){

    var obj = {};

    // Try to login using the provided username and password
    obj.login = function( username, password, onSuccess, onError ){
        modules.http.post( 
            '/api/login', 
            {
                "username": username,
                "password": password
            },
            function( response ){ 
                if( response.status < 299 && response.bodyJson.success )
                    onSuccess( response.bodyJson.user );
                else
                    onError( response );
            }
        );
    }

    // get the information pertinent to the currently logged in user
    obj.currentUser = function( onSuccess, onError ){
        modules.http.get( '/api/current-user', function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess( response.bodyJson.user );
            else
                onError( response );
        });
    }

    // Logs out the user
    obj.logout = function( onSuccess, onError ){
        modules.http.get( '/api/logout', function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess();
            else
                onError( response );
        });
    }

    var modules = system.addModule();
    modules.api.security = obj;

})();
