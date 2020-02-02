(function(){

    var obj = {};

    // details about all users
    obj.list = function( onSuccess, onError ){
        modules.http.get( '/api/users', function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess( response.bodyJson.records );
            else
                onError( response );
        });
    }

    // creates a new user
    obj.create = function( username, name, email, isAdmin, onSuccess, onError ){
        modules.http.post( '/api/users', {
                "username": username,
                "name": name,
                "email": email,
                "isAdmin": isAdmin
            }, function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess();
            else
                onError( response );
        });
    }

    // update a user's details
    obj.update = function( userId, username, name, email, isAdmin, isEnabled, onSuccess, onError ){
        modules.http.put( '/api/users/' + userId, {
                "username": username,
                "name": name,
                "email": email,
                "isAdmin": isAdmin,
                "isEnabled": isEnabled
            }, function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess();
            else
                onError( response );
        });
    }

    var modules = system.addModule();
    if( modules.api.admin == undefined )
        modules.api.admin = {};
    modules.api.admin.users = obj;

})();
