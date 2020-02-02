
(function(){

    var pages = [];
    var currentPage = undefined;
    var currentModal = undefined;

    var fnDisplayPage = function( requiredPageId ){
        pages.forEach( function( page ){ 
            if( page.id == requiredPageId && page.isModal == false ){
                if( currentPage != undefined ){
                    currentPage.onHide();
                }
                currentPage = page;
                currentPage.onShow();
            }
        });
    }

    var fnDisplayModal = function( requiredPageId, obj ){
        pages.forEach( function( page ){ 
            if( page.id == requiredPageId && page.isModal == true ){
                if( currentModal != undefined ){
                    currentModal.onHide();
                }
                if ($(".modal-backdrop").length > 0) {
                    $(".modal-backdrop").remove();
                }                
                currentModal = page;
                currentModal.onShow( obj );
            }
        });
    }

    var fnGetPagesIdByAttributeTrue = function( attribute ){
        var arr = [];
        pages.forEach( function( page ){ 
            if( page[ 'isModal' ] == false && page[ attribute ] == true ){
                arr.push( page );
            }
        });
        return arr;
    }

    var fnPagesAnonymous = function(){
        return fnGetPagesIdByAttributeTrue( 'anonymous' );
    }

    var fnPagesUser = function(){
        return fnGetPagesIdByAttributeTrue( 'user' );
    }

    var fnPagesAdmin = function(){
        return fnGetPagesIdByAttributeTrue( 'isAdmin' );
    }

    var fnRegister = function( obj ){
        pages.push( obj );
    }

    var modules = system.addModule( 'page', {
        displayPage: fnDisplayPage,
        displayModal: fnDisplayModal,
        register: fnRegister,
        anonymousPages: fnPagesAnonymous,
        userPages: fnPagesUser,
        adminPages: fnPagesAdmin,
    });


})();
