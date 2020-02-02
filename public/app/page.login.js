
(function(){

    var id = 'idLogin';

    var modules = system.addModule();

    var fnOnShow = function(){
        $('#idPageContainer').html( modules.template.get( id ) );

        var form = $("#idLogin_form")[0];
        form.classList.remove('was-validated');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            var isValid = true;
            if (form.checkValidity() === false) {
                event.stopPropagation();
                isValid = false;
            }
            form.classList.add('was-validated');
            if( isValid ){
                modules.security.login( $('#idLogin_username').val(), $('#idLogin_password').val(), function(){
                    modules.page.displayPage('idUserWorkFromHome');
                }, function(){
                    modules.page.displayPage('idLogin');
                });
                modules.page.displayPage('idLoading');
            }
        }, false);
    }

    var fnOnHide = function(){
        $( "#" + id ).remove();
    }

    var fnOnNavigationClick = function(){}

    var obj = {
        id: id,
        isModal: false,
        anonymous: true,
        user: false,
        isAdmin: false,
        onShow: fnOnShow,
        onHide: fnOnHide,
        onNavigationClick: fnOnNavigationClick,
    }

    modules.page.register( obj );
    modules.template.load( id, '/templates/login.html' );


})();
