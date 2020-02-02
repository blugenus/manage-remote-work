(function(){

    var id = 'idAdminUsers_new_modal';

    var modules = system.addModule();

    var newRequestCreatedCallback;

    var fnCreateNewSubmit = function( event ){
        var forms = $("#modalForm1");
        event.preventDefault();
        var isValid = true;
        if (forms[0].checkValidity() === false) {
            event.stopPropagation();
            isValid = false;
        }
        forms[0].classList.add('was-validated');
        if( isValid ){
            modules.api.admin.users.create(
                $('#' + id + ' .requestUsername').val(), 
                $('#' + id + ' .requestName').val(), 
                $('#' + id + ' .requestEmail').val(), 
                $('#' + id + ' .requestIsAdmin').is(":checked"), 
                function(){
                    $('#' + id + ' div.modal').modal('hide');
                    newRequestCreatedCallback();
                }, function( response ){

                }
            );
        }        
    }

    var fnOnShow = function( requestCreatedCallback ){
        newRequestCreatedCallback = requestCreatedCallback;
        $( "#" + id ).remove();
        $('#idModalContainer').html( modules.template.get( id ) );        

        var forms = $("#modalForm2");
        forms.hide();

        var forms = $("#modalForm1");

        forms.on('submit', fnCreateNewSubmit );

        $('#' + id + ' div.modal .cmdUpdate').hide();
        $('#' + id + ' div.modal .cmdLicenses').hide();
        $('#' + id + ' div.modal .isEnabledArea').hide();
        $('#' + id + ' div.modal .cmdCreate').bind('click', function(event) {
            $("#modalForm1").submit();
        });

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
