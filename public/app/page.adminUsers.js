
(function(){

    var id = 'idAdminUsers';

    var modules = system.addModule();

    var records = [];

    var prepareRecords = function( records ){
        for( coun=0; coun<records.length; coun++ ){
            var newRow = '<tr>';
            newRow += '<td>' + records[coun]['userId'] + '</td>'; 
            newRow += '<td>' + records[coun]['username'] + '</td>'; 
            newRow += '<td>' + records[coun]['name'] + '</td>'; 
            newRow += '<td>' + records[coun]['email'] + '</td>'; 
            newRow += '<td>' + (records[coun]['isAdmin'] == 1? 'Y': 'N') + '</td>'; 
            newRow += '<td>' + (records[coun]['isEnabled'] == 1? 'Y': 'N') + '</td>'; 
            newRow += '</tr>';
            $('#' + id + ' table tbody').append( newRow );
        }        
        $('#' + id + ' table tbody tr').click( function( event ){
            
            for( coun=0; coun<records.length; coun++ ){
                if( records[coun]['userId'] == event.delegateTarget.cells[0].innerHTML ){
                    modules.page.displayModal( 'idAdminUsers_edit_modal', { 
                        record: records[coun], 
                        callback: fnRequestData 
                    });
                    break;
                }
            }
        });
    }

    var fnRequestData = function(){
        $('#' + id + ' table tbody').empty();
        modules.api.admin.users.list( function( records ){
            prepareRecords( records );
        }, function(){

        });
    }

    var fnOnShow = function(){

        $('#idPageContainer').html( modules.template.get( id ) );

        $('#' + id + ' h4 button.cmdNew').unbind().bind( "click", function( event ){
            modules.page.displayModal( 'idAdminUsers_new_modal', fnRequestData );
        });
        $('#' + id + ' table tbody').empty();
        records = [];
        fnRequestData();
    }

    var fnOnHide = function(){
        $( "#" + id ).remove();
    }

    var fnOnNavigationClick = function(){
        modules.page.displayPage( id );
    }

    var obj = {
        id: id,
        isModal: false,
        anonymous: false,
        user: false,
        isAdmin: true,
        onShow: fnOnShow,
        onHide: fnOnHide,
        onNavigationClick: fnOnNavigationClick,
    }

    modules.page.register( obj );
    modules.template.load( id, '/templates/adminUsers.html' );

})();
