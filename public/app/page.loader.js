
(function(){

    var id = 'idLoading';

    var modules = system.addModule();

    var fnOnShow = function(){}

    var fnOnHide = function(){}

    var fnOnNavigationClick = function(){}

    var obj = {
        id: id,
        isModal: false,
        anonymous: false,
        user: false,
        isAdmin: false,
        onShow: fnOnShow,
        onHide: fnOnHide,
        onNavigationClick: fnOnNavigationClick,
    }

    modules.page.register( obj );
    modules.template.load( id, '/templates/loading.html' );

})();
