
(function(){

    var id = 'idLogout';

    var modules = system.addModule();

    var fnOnShow = function(){}

    var fnOnHide = function(){}

    var fnOnNavigationClick = function(){
        modules.security.logout( function(){
            modules.page.displayPage('idLogin');
        }, function(){
            modules.page.displayPage('idLogin');
        });
        modules.page.displayPage('idLoading');
    }

    var obj = {
        id: id,
        isModal: false,
        anonymous: false,
        user: true,
        isAdmin: true,
        onShow: fnOnShow,
        onHide: fnOnHide,
        onNavigationClick: fnOnNavigationClick,
    }

    modules.page.register( obj );


})();
