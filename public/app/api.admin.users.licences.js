(function(){

    var obj = {};

    // licenses information for the selected user.
    obj.list = function( userId, onSuccess, onError ){
        modules.http.get( '/api/users/' + userId + '/licenses', function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess( response.bodyJson.records );
            else
                onError( response );
        });
    }

    // update the licensing information for a user.
    obj.update = function( userId, arrayOfLicences, onSuccess, onError ){
        modules.http.put( '/api/users/' + userId + '/licenses', arrayOfLicences, function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess();
            else
                onError( response );
        });
    }

    var modules = system.addModule();
    if( modules.api.admin == undefined )
        modules.api.admin = {};
    if( modules.api.admin.users == undefined )
        modules.api.admin.users = {};
    modules.api.admin.users.licenses = obj;


})();
