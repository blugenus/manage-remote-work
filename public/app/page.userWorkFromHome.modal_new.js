(function(){

    var id = 'idUserWorkFromHome_new_modal';

    var modules = system.addModule();

    var newRequestCreatedCallback;

    var fnCreateNewSubmit = function( event ){
        var forms = $("#modal_new_form");
        event.preventDefault();
        var isValid = true;
        if (forms[0].checkValidity() === false) {
            event.stopPropagation();
            isValid = false;
        }
        forms[0].classList.add('was-validated');
        if( isValid ){
            modules.api.user.workFromHome.create( 
                $('#modal_date').val(), 
                $('#modal_hours').val(), 
                $('#modal_comment').val(), 
                function(){
                    $('#' + id + ' div.modal').modal('hide');
                    newRequestCreatedCallback();
                }, function(){
                    
                }
            );
        }        
    }

    var fnOnShow = function( requestCreatedCallback ){
        newRequestCreatedCallback = requestCreatedCallback;
        $( "#" + id ).remove();
        $('#idModalContainer').html( modules.template.get( id ) );        

        var forms = $("#modal_new_form");

        forms.on('submit', fnCreateNewSubmit );

        // A request must be made at least 4 hours before the end of the previous day
        var dt = new Date();
        if( dt.getHours() >= 20 ){
            dt.setDate(dt.getDate() + 2);
        }else{
            dt.setDate(dt.getDate() + 1);
        }
        $( '#modal_date' )[0].min = dt.toISOString().split('T')[0];        

        $('#' + id + ' div.modal .cmdCreate').bind('click', function(event) {
            $("#modal_new_form").submit();
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
    modules.template.load( id, '/templates/userWorkFromHome_new_modal.html' );


})();
