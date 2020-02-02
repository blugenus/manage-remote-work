    
(function(){

    var template = {};

    var fnLoad = function( name, url ){
        template[ name ] = undefined;
        var element = $('<div></div>');
        element.load( url, function( responseText, textStatus, jqXHR ){
            //console.log( responseText, textStatus, jqXHR );
            if( jqXHR.status == 200 ){
                template[ name ] = '<div id="' + name + '">' + responseText + '</div>';
            } else {
                console.log( 'error downloading template ' + name + ' - url' );
                template[ name ] = '<div>error downloading template ' + name + '</div>';
            }
        });
    }

    var fnGet = function( name ){
        return template[ name ];
    }

    var fnReady = function(){
        keys = Object.keys(template)
        keys.forEach( function( key ){
            if( template[ key ] == undefined ){
                return false;
            }
        });
        return true;
    }

    system.addModule( 'template', {
        load: fnLoad,
        get: fnGet,
        ready: fnReady
    } );

})();
