(function(){

    var id = 'idAdminUsersLicenses_modal';

    var modules = system.addModule();

    var licenses; 

    var prepareRecords = function( records ){
        licenses = records;
        $('#' + id + ' .spinnerToRemove').remove();
        for( coun=0; coun<records.length; coun++ ){
            var newCheckbox = '<div class="form-check">';
            newCheckbox += '<input class="form-check-input license" '
            newCheckbox += ' type="checkbox" ';
            newCheckbox += ' id="modal_check' + records[coun]['licenseId'] + '" >';
            newCheckbox += '<label class="form-check-label" for="modal_check' + records[coun]['licenseId'] + '" ';
            //newCheckbox += (records[coun]['isTicked'] == 1)?'checked': '';
            newCheckbox += ' >';
            newCheckbox += records[coun]['productName'];
            newCheckbox += '</label>';
            newCheckbox += '</div>';
            $('#modalForm1').append( newCheckbox );
            if( (records[coun]['isTicked'] == 1) ){
                $( '#modal_check' + licenses[coun].licenseId ).prop( 'checked', true );
            }
        }        

    }

    var fnUpdate = function( obj ){
        for( coun=0; coun<licenses.length; coun++ ){
            licenses[coun].isTicked = $( '#modal_check' + licenses[coun].licenseId ).is(":checked");
        }
        modules.api.admin.users.licenses.update(
            obj.record['userId'], 
            licenses, 
            function(){
                modules.page.displayModal( 'idAdminUsers_edit_modal', obj );
            }, function( response ){

            }
        );
    }

    var fnRequestData = function( userId ){
        modules.api.admin.users.licenses.list( userId, function( records ){
            prepareRecords( records );
        }, function(){

        });
    }

    /*
     * obj  has 2 attributes record and callback
    */
    var fnOnShow = function( obj ){

        $( "#" + id ).remove();
        $('#idModalContainer').html( modules.template.get( id ) );        

        $('#' + id + ' .labelUsername').html( obj.record['username'] );

        $('#' + id + ' div.modal .cmdUpdate').bind('click', function(event) {
            fnUpdate( obj );
        });
        $('#' + id + ' div.modal .cmdUser').bind('click', function(event) {
            modules.page.displayModal( 'idAdminUsers_edit_modal', obj );
        });

        fnRequestData( obj.record['userId'] );

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
    modules.template.load( id, '/templates/adminUsersLicenses_modal.html' );


})();
