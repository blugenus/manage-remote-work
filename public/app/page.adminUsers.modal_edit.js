(function(){

    var id = 'idAdminUsers_edit_modal';

    var modules = system.addModule();

    var actionCallback;
    var userId;

    var fnDisplayInformation = function( record ){
        userId = record['userId'];

        $('#' + id + ' .requestUsername').val( record['username'] );
        $('#' + id + ' .requestName').val( record['name'] );
        $('#' + id + ' .requestEmail').val( record['email'] );
        $('#' + id + ' .requestIsAdmin').prop( 'checked', record['isAdmin'] == 1 );
        $('#' + id + ' .requestIsEnabled').prop( 'checked', record['isEnabled'] == 1 );
    }

    var fnUpdateNewSubmit = function( event ){
        var forms = $("#modalForm1");
        event.preventDefault();
        var isValid = true;
        if (forms[0].checkValidity() === false) {
            event.stopPropagation();
            isValid = false;
        }
        forms[0].classList.add('was-validated');
        if( isValid ){
            modules.api.admin.users.update(
                userId, 
                $('#' + id + ' .requestUsername').val(), 
                $('#' + id + ' .requestName').val(), 
                $('#' + id + ' .requestEmail').val(), 
                $('#' + id + ' .requestIsAdmin').is(":checked"), 
                $('#' + id + ' .requestIsEnabled').is(":checked"), 
                function(){
                    $('#' + id + ' div.modal').modal('hide');
                    actionCallback();
                }, function( response ){

                }
            );
        }        
    }

    /*
     * obj  has 2 attributes record and callback
    */
    var fnOnShow = function( obj ){
        actionCallback = obj.callback;

        $( "#" + id ).remove();
        $('#idModalContainer').html( modules.template.get( id ) );        

        // var forms = $("#modalForm2");
        // forms.hide();

        $('#' + id + ' h5').html( 'Edit Employee' );

        var forms = $("#modalForm1");
        forms.on('submit', fnUpdateNewSubmit );

        $('#' + id + ' div.modal .cmdCreate').hide();
        $('#' + id + ' div.modal .cmdUpdate').bind('click', function(event) {
            $("#modalForm1").submit();
        });
        $('#' + id + ' div.modal .cmdLicenses').bind('click', function(event) {
            // $('#' + id + ' div.modal').modal('hide');
            // fnOnHide();
            modules.page.displayModal( 'idAdminUsersLicenses_modal', obj );
        });

        fnDisplayInformation( obj.record );

        $('#' + id + ' div.modal').modal('show');
    }

    var fnOnHide = function( callback ){
        $( "#" + id ).modal('hide');
        $( "#" + id ).remove();
    }

    var fnOnNavigationClick = function(){}

    var obj = {
        id: id,
        isModal: true,
        anonymous: false,
        user: false,
        isAdmin: false,
        onShow: fnOnShow,
        onHide: fnOnHide,
        onNavigationClick: fnOnNavigationClick,
    }

    modules.page.register( obj );
    modules.template.load( id, '/templates/adminUsers_edit_modal.html' );


})();
