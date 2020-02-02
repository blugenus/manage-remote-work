// created system object 
window.system = (function(){

    // init of modules object.
    var modules = {
        api: {}
    };

    var fnAddModule = function( name, object ){
        if( name != undefined ){ // simple check to skip the adding part and simply return the modules object
            modules[name] = object;
        }
        return modules;
    }
    
    var fnInit = function(){
        // by default display the page with idLoading 
        modules.page.displayPage('idLoading');
        // setup the menus
        modules.navigation.setMenu();
        // get currently logged in information
        modules.security.getCurrentUser( function(){
            // on success go to the user's own work from homew requests.
            modules.page.displayPage('idUserWorkFromHome');
        }, function(){
            // on failure / not logged in go to the login page.
            modules.page.displayPage('idLogin');
        });
    }

    return {
        init: fnInit,
        addModule: fnAddModule,
    }
})();