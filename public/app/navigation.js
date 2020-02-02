
(function(){

    var fnSetMenu = function(){
        var pages = modules.security.getAccessLevel();
        $(".navbar a.nav-link").each( function( index, element ){ 
            var jElement = $( element );
            var value = jElement.data('value');
            jElement.hide();
            pages.forEach( function( page ){ 
                if( page.id == value ){
                    jElement.show();
                    jElement.unbind();
                    jElement.bind( "click", page.onNavigationClick );
                }
            });
        });
    }

    var modules = system.addModule( 'navigation', {
        setMenu: fnSetMenu,
    });


})();
