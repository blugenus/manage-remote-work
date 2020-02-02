(function(){

    var obj = {};

    // List the work from home requests from all users
    obj.list = function( onSuccess, onError ){
        modules.http.get( '/api/users/work-from-home', function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess( response.bodyJson.records );
            else
                onError( response );
        });
    }

    // reject a work from home request
    obj.decline = function( requestId, comment, onSuccess, onError ){
        modules.http.patch( '/api/users/work-from-home/' + requestId + '/decline', 
            { comment: comment }, 
            function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess();
            else
                onError( response );
        });
    }

    // approve a work from home request.
    obj.approve = function( requestId, comment, onSuccess, onError ){
        modules.http.patch( '/api/users/work-from-home/' + requestId + '/approve', 
            { comment: comment }, 
            function( response ){ 
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
    modules.api.admin.users.workFromHome = obj;


})();