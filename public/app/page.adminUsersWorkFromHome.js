
(function(){

    var id = 'idAdminWorkFromHome';

    var modules = system.addModule();
    var records = [];

    var prepareRecords = function(){
        var filter = $('#idHomeFromWorkFilter').val();
        $('#' + id +' .listRecords tbody').empty();
        for( coun=0; coun<records.length; coun++ ){
            if( filter == 'all' || filter == records[coun]['statusId'] ){
                var dt = new Date( records[coun]['requestDate'] );
                records[coun]['dateHumanFormat'] = dt.getDate()+'/'+(dt.getMonth()+1)+'/'+dt.getFullYear();
                var newRow = '<tr>';
                newRow += '<td>' + records[coun]['requestId'] + '</td>'; 
                newRow += '<td>' + records[coun]['employee'] + '</td>'; 
                newRow += '<td>' + records[coun]['dateHumanFormat'] + '</td>'; 
                newRow += '<td>' + records[coun]['requestHours'] + '</td>'; 
                newRow += '<td>' + records[coun]['status'] + '</td>'; 
                newRow += '</tr>';
                $('#' + id +' .listRecords tbody').append( newRow );
            }
        }
        $('#' + id +' .listRecords tbody tr').click( function( event ){
            for( coun=0; coun<records.length; coun++ ){
                if( records[coun]['requestId'] == event.delegateTarget.cells[0].innerHTML ){
                    modules.page.displayModal( 'idAdminWorkFromHome_edit_modal', { 
                        record: records[coun], 
                        cancelledCallback: fnRequestData 
                    });
                    break;
                }
            }
        });
    }

    var fnRequestData = function(){
        $('#' + id +' .listRecords tbody').empty();
        modules.api.admin.users.workFromHome.list( function( data ){
            records = data;
            prepareRecords();
        }, function(){

        });
    }

    var fnOnShow = function(){
        $('#idPageContainer').html( modules.template.get( id ) );
        $('#idHomeFromWorkFilter').change( function( data ){
            //console.log( $('#idHomeFromWorkFilter').val(), data );
            prepareRecords()
        })
        $('#' + id +' .listRecords tbody').empty();
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
    modules.template.load( id, '/templates/adminUsersWorkFromHome.html' );

})();
