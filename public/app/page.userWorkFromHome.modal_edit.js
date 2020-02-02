
(function(){

    var id = 'idUserWorkFromHome_edit_modal';

    var modules = system.addModule();

    var requestCancelledCallback;
    var requestId;

    var fnDisplayInformation = function( record ){
        requestId = record['requestId']
        $('#' + id + ' .adminEditArea').hide();
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

        switch( record[ 'statusId' ] ){
            case 0: // pending
                $('#' + id + ' .adminArea').hide();
                $('#' + id + ' .cancelRequest').show();
                break;
            case 1: // approved
            case -1: // declined
                $('#' + id + ' .adminArea').show();
                $('#' + id + ' .cancelRequest').hide();
                break;
            default: // -2 canceled by user
                $('#' + id + ' .adminArea').hide();
                $('#' + id + ' .cancelRequest').hide();
                break;
        }
    }

    var fnCancelRequest = function(){
        modules.api.user.workFromHome.cancel( requestId, function(){
            $('#' + id + ' div.modal').modal('hide');
            requestCancelledCallback();            
        }, function(){
            
        });
    }

    /*
     * obj  has 2 attributes record and cancelledCallback
    */
    var fnOnShow = function( obj ){
        requestCancelledCallback = obj.cancelledCallback;

        $( "#" + id ).remove();
        $('#idModalContainer').html( modules.template.get( id ) );

        $('#' + id + ' .cancelRequest').bind('click', function(event) {
            fnCancelRequest();
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
