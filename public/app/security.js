(function(){

    var security = {};

    var fnSetSecurity = function( user ){
        security.loggedIn = user.userId != 0;
        security.username = user.username;
        security.isAdmin = user.isAdmin;
        if( modules != undefined && modules.navigation != undefined ){
            modules.navigation.setMenu();
        }
    }

    var fnResetSecurity = function(){
        fnSetSecurity({
            userId: 0,
            isAdmin: false,
            username: ''
        });
    }

    fnResetSecurity();

    var fnGetAccessLevel = function(){
        if( !security.loggedIn ) // Anonymous
            return modules.page.anonymousPages();
        if( !security.isAdmin ) // User
            return modules.page.userPages();
        // Admin
        return modules.page.adminPages();
    }

    var fnGetCurrentUser = function( onSuccess, onError ){
        modules.api.security.currentUser( function( user ){
            fnSetSecurity( user );
            onSuccess();
        }, 
        function(){
            onError();
        });
    }

    var fnLogin = function( username, password, onSuccess, onError ){
        modules.api.security.login( 
            username, 
            password, 
            function( user ){
                fnSetSecurity( user );
                onSuccess();
            }, 
            function(){
                onError();
            }
        );
    }

    var fnLogout = function( onSuccess, onError ){
        modules.api.security.logout( function(){
            fnResetSecurity();
            onSuccess();
        }, 
        function(){
            onError();
        });
    }

    var modules = system.addModule( 'security', {
        getCurrentUser: fnGetCurrentUser,
        getAccessLevel: fnGetAccessLevel,
        login: fnLogin,
        logout: fnLogout,
    });


})();

