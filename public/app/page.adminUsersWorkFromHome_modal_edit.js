
(function(){

    var id = 'idAdminWorkFromHome_edit_modal';

    var modules = system.addModule();

    var requestCancelledCallback;
    var requestId;

    var fnDisplayInformation = function( record ){
        requestId = record['requestId']
        $('#' + id + ' .requestNo')[0].innerHTML = requestId;
        $('#' + id + ' .requestDate')[0].innerHTML = record['dateHumanFormat'];
        $('#' + id + ' .requestHours')[0].innerHTML = record['requestHours'];
        $('#' + id + ' .requestComment')[0].innerHTML = record['requestComment'];
        $('#' + id + ' .requestStatus')[0].innerHTML = record['status'];
        $('#' + id + ' .requestAdmin')[0].innerHTML = record['admin'];
        $('#' + id + ' .requestAdminComment')[0].innerHTML = record['adminComment'];
    }

    var fnConfigureButtons = function( record ){
        $('#' + id + ' .declineRequest').hide();
        $('#' + id + ' .approveRequest').hide();
        $('#' + id + ' .cancelRequest').hide();
        $('#' + id + ' .adminArea').hide();
        $('#' + id + ' .adminEditArea').hide();

        switch( record[ 'statusId' ] ){
            case 0: // pending
                $('#' + id + ' .declineRequest').show();
                $('#' + id + ' .approveRequest').show();
                $('#' + id + ' .adminEditArea').show();
                break;
            case 1: // approved
            case -1: // declined
                $('#' + id + ' .adminArea').show();
                break;
            default: // -2 canceled by user
                break;
        }
    }

    var fnDeclineRequest = function(){
        modules.api.admin.users.workFromHome.decline( requestId, $('#' + id + ' .adminComment').val(), function(){
            $('#' + id + ' div.modal').modal('hide');
            requestCancelledCallback();            
        }, function(){
            
        });
    }

    var fnApproveRequest = function(){
        modules.api.admin.users.workFromHome.approve( requestId, $('#' + id + ' .adminComment').val(), function(){
            $('#' + id + ' div.modal').modal('hide');
            requestCancelledCallback();            
        }, function(){
            
        });
    }

    /*
     * obj  has 2 keys record and cancelledCallback
    */
    var fnOnShow = function( obj ){
        requestCancelledCallback = obj.cancelledCallback;

        $( "#" + id ).remove();
        $('#idModalContainer').html( modules.template.get( id ) );

        $('#' + id + ' .declineRequest').bind('click', function(event) {
            fnDeclineRequest();
        });

        $('#' + id + ' .approveRequest').bind('click', function(event) {
            fnApproveRequest();
        });

        fnDisplayInformation( obj.record );
        fnConfigureButtons( obj.record );

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
    modules.template.load( id, '/templates/workFromHome_edit_modal.html' );


})();
