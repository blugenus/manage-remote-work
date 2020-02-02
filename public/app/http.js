    
(function(){

    var fnHttp = function( method, url, postData, callback ){
        var request = {
            headers : {
                'Accept' : 'application/json',
                'Content-Type' : 'application/json'
            },
            url : url,
            type : method,
            success : function( response, textStatus, jqXhr ) {
            },
            error : function( jqXhr, textStatus, errorThrown ) {
            },
            complete : function( jqXhr, textStatus ) {
                if( jqXhr.status > 299 ){ 
                    console.log( jqXhr.responseJSON ); 
                    if( jqXhr.responseJSON != undefined && jqXhr.responseJSON.message != undefined ){
                        alert( jqXhr.responseJSON.message );
                    }
                }

                if( !jqXhr.responseJSON.success ){
                    console.log( jqXhr.responseJSON );
                }

                if( callback != undefined ){
                    callback({
                        status: jqXhr.status, 
                        bodyJson: jqXhr.responseJSON
                    });
                }
            }
        }

        if( postData != undefined )
            request.data = JSON.stringify( postData );

        $.ajax( request );
    }

    system.addModule( 'http', {
        get: function( url, callback ){
            fnHttp( 'GET', url, undefined, callback );
        },
        post: function( url, postData = undefined, callback = undefined){
            fnHttp( 'POST', url, postData, callback );
        },
        put: function( url, postData = undefined, callback = undefined ){
            fnHttp( 'PUT', url, postData, callback );
        },
        patch: function( url, postData = undefined, callback = undefined ){
            fnHttp( 'PATCH', url, postData, callback );
        },
    });

})();
