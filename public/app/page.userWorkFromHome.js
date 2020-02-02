
(function(){

    var id = 'idUserWorkFromHome';

    var modules = system.addModule();

    var records = [];

    var prepareRecords = function( records ){
        for( coun=0; coun<records.length; coun++ ){
            var dt = new Date( records[coun]['requestDate'] );
            records[coun]['dateHumanFormat'] = dt.getDate()+'/'+(dt.getMonth()+1)+'/'+dt.getFullYear();
            var newRow = '<tr>';
            newRow += '<td>' + records[coun]['requestId'] + '</td>'; 
            newRow += '<td>' + records[coun]['dateHumanFormat'] + '</td>'; 
            newRow += '<td>' + records[coun]['requestHours'] + '</td>'; 
            newRow += '<td>' + records[coun]['status'] + '</td>'; 
            newRow += '</tr>';
            $('#' + id + ' table tbody').append( newRow );
        }
        $('#' + id + ' table tbody tr').click( function( event ){
            for( coun=0; coun<records.length; coun++ ){
                if( records[coun]['requestId'] == event.delegateTarget.cells[0].innerHTML ){
                    modules.page.displayModal( 'idUserWorkFromHome_edit_modal', { 
                        record: records[coun], 
                        cancelledCallback: fnRequestData 
                    });
                    break;
                }
            }
        });
    }

    var fnRequestData = function(){
        $('#' + id + ' table tbody').empty();
        modules.api.user.workFromHome.list( function( records ){
            prepareRecords( records );
        }, function(){

        });
    }

    var fnOnShow = function(){

        $('#idPageContainer').html( modules.template.get( id ) );

        $('#' + id + ' h4 button.cmdNew').unbind().bind( "click", function( event ){
            modules.page.displayModal( 'idUserWorkFromHome_new_modal', fnRequestData );
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
        user: true,
        isAdmin: true,
        onShow: fnOnShow,
        onHide: fnOnHide,
        onNavigationClick: fnOnNavigationClick,
    }

    modules.page.register( obj );
    modules.template.load( id, '/templates/userWorkFromHome.html' );

})();
