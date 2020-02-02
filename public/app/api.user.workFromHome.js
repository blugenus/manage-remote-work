/*
 * apis needed for user to create or cancel work from home request
 */
(function(){

    var obj = {};

    // get all the work from home requests of the logged in user
    obj.list = function( onSuccess, onError ){
        modules.http.get( '/api/user/work-from-home', function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess( response.bodyJson.records );
            else
                onError( response );
        });
    }

    // The logged in user creates a new work from home request
    obj.create = function( date, hours, comment, onSuccess, onError ){
        modules.http.post( '/api/user/work-from-home', {
                "date": date,
                "hours": hours,
                "comment": comment
            }, function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess();
            else
                onError( response );
        });
    }

    // The logged in user cancels on of his work from home requests
    obj.cancel = function( requestId, onSuccess, onError ){
        modules.http.patch( '/api/user/work-from-home/' + requestId + '/cancel', 
            {}, 
            function( response ){ 
            if( response.status < 299 && response.bodyJson.success )
                onSuccess();
            else
                onError( response );
        });
    }

    var modules = system.addModule();
    modules.api.user = {};
    modules.api.user.workFromHome = obj;

})();
