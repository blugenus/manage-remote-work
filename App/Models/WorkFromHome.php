<?php
namespace App\Models;

class WorkFromHome extends \App\Model {

// Methods for Administrators 

    /**
     * select Work from Home requests from all users.
     *
     * @return Array
     */
    public static function selectAll(){
        $result = static::bindAndQuery( "
            SELECT
                *, 
                (SELECT `description` FROM `work_from_home_status` WHERE `statusId` = `work_from_home`.`statusId`) `status`,
                (SELECT `name` FROM `users` WHERE `userId` = `work_from_home`.`userId`) AS `employee`,
                IFNULL((SELECT `name` FROM `users` WHERE `userId` = `work_from_home`.`adminUserId`),'') AS `admin`
            FROM `work_from_home`
            order by `requestDate` desc;
        " );
        return $result['records'];
    }

    /**
     * returns the information on a specific Work from Home
     *
     * @param Int $requestId  The request Id of the required Work from Home request.
     * 
     * @return Array
     */
    private static function selectById( $requestId ){
        $result = static::bindAndQuery( 
            "SELECT * FROM `work_from_home` WHERE `requestId` = ?;",
            'i',
            [ &$requestId ]
        );
        return $result['records'];
    }

    /**
     * Rejecting a work order
     *
     * @param Int $requestId  The request Id of the Work from Home request being rejected.
     * @param String $comment  The comment of the administrator
     * 
     * @return Boolean
     */
    public static function declineRequest( $requestId, $comment ){
        $userId = \App\Security::getCurrentUserId();
        $result = static::bindAndQuery( 
            "UPDATE `work_from_home` SET `statusId` = -1, `closedDateTime` = NOW(), `adminUserId` = ?, `adminComment` = ? WHERE `requestId` = ? and `statusId` = 0;",
            'isi',
            [ 
                &$userId,
                &$comment,
                &$requestId 
            ]
        );
        $request = static::selectById( $requestId );
        if( sizeof( $request ) == 1 ){
            $user = User::selectById( $request[0]['userId'] );
            static::sendDeclineEmail( 
                $user[0]['email'], 
                $user[0]['name'], 
                $requestId, 
                $request[0]['requestDate'], 
                $request[0]['requestHours'] 
            );
        }

        return true;
    }

    /**
     * Approving a work order
     *
     * @param Int $requestId  The request Id of the Work from Home request being approved.
     * @param String $comment  The comment of the administrator
     * 
     * @return Boolean
     */
    public static function approveRequest( $requestId, $comment ){
        $userId = \App\Security::getCurrentUserId();
        $result = static::bindAndQuery( 
            "UPDATE `work_from_home` SET `statusId` = 1, `closedDateTime` = NOW(), `adminUserId` = ?, `adminComment` = ? WHERE `requestId` = ? and `statusId` = 0;",
            'isi',
            [ 
                &$userId,
                &$comment,
                &$requestId 
            ]
        );
        $request = static::selectById( $requestId );
        if( sizeof( $request ) == 1 ){
            $user = User::selectById( $request[0]['userId'] );
            static::sendApproveEmail( 
                $user[0]['email'], 
                $user[0]['name'], 
                $requestId, 
                $request[0]['requestDate'], 
                $request[0]['requestHours'] 
            );
        }

        return true;
    }

// Methods for normal users 

    /**
     * return all the Work from Home requests for the current logged in user. 
     *
     * @return Array
     */
    public static function selectAllForLoggedInUser(){
        $userId = \App\Security::getCurrentUserId();
        $result = static::bindAndQuery( "
                SELECT 
                    *, 
                    (SELECT `description` FROM `work_from_home_status` WHERE `statusId` = `work_from_home`.`statusId`) `status`,
                    (SELECT `name` FROM `users` WHERE `userId` = `work_from_home`.`userId`) AS `employee`,
                    IFNULL((SELECT `name` FROM `users` WHERE `userId` = `work_from_home`.`adminUserId`),'') AS `admin`
                FROM `work_from_home` WHERE `userId` = ?
                order by `requestDate` desc;
            ",
            'i',
            [ &$userId ]
        );
        return $result['records'];
    }

    /**
     * A user creating a new Work from Home request
     *
     * @param String $date  the date of when the user want to work from home. 
     * @param Int $hours  the number of hours the user wants to work fro home. 
     * @param String $comment  The comment of the user creating the request.
     * 
     * @return Array  only contains insertId
     */
    public static function createForLoggedInUser( String $date, Int $hours, String $comment ){
        $userId = \App\Security::getCurrentUserId();
        $result = static::bindAndQuery( 
            "INSERT INTO `work_from_home` ( `createdDateTime`, `userId`, `requestDate`, `requestHours`, `requestComment` ) VALUE ( NOW(),?,?,?,?);",
            'isis',
            [ 
                &$userId, 
                &$date,
                &$hours,
                &$comment
            ]
        );
        return [
            'insertId' => $result['insertId']
        ];
    }

    /**
     * A user cancelling his Work from Home request
     *
     * @param Int $requestId  The request Id of the Work from Home request being cancelled.
     * 
     * @return Boolean
     */
    public static function cancelRequestForLoggedInUser( Int $requestId ){
        $userId = \App\Security::getCurrentUserId();
        // Not using `statusId` = 0 in the where statement as employee might want to cancel approved time. 
        $result = static::bindAndQuery( 
            "UPDATE `work_from_home` SET `statusId` = -2, `closedDateTime` = NOW() WHERE `requestId` = ? AND `userId` = ?;",
            'ii',
            [ 
                &$requestId, 
                &$userId
            ]
        );
        return true;
    }

    /**
     * sending the email template for the declined request 
     *
     * could have user twig here... 
     *
     * @param String $email  The email of the user who requested to work from home. 
     * @param String $name  The name of the user who requested to work from home.
     * @param Int $requestId  The username of the logged in user
     * @param String $date  the date of when the user want to work from home. 
     * @param Int $hours  the number of hours the user wants to work fro home. 
     * 
     * @return void
     */
    public static function sendDeclineEmail( String $email, String $name, Int $requestId, String $date, Int $hours ){
        $body = "
        <p>Dear $name</p>
        <p>I am sorry to inform you that your request to work $hours from home on " . date("l F j, Y", strtotime($date) ) . " has been rejected.</p>
        <p>Your request reference is $requestId.</p>
        <p>The management</p>
        ";
        \App\Smtp::send( 
            $email, 
            'Your request has been rejected', 
            $body
        );
    }

    /**
     * sending the email template for the approved request 
     *
     * could have user twig here... 
     *
     * @param String $email  The email of the user who requested to work from home. 
     * @param String $name  The name of the user who requested to work from home.
     * @param Int $requestId  The username of the logged in user
     * @param String $date  the date of when the user want to work from home. 
     * @param Int $hours  the number of hours the user wants to work fro home. 
     * 
     * @return void
     */
    public static function sendApproveEmail( String $email, String $name, Int $requestId, String $date, Int $hours ){
        $body = "
        <p>Dear $name</p>
        <p>I am happy to inform you that your request to work $hours from home on " . date("l F j, Y", strtotime($date) ) . " has been approved.</p>
        <p>Your request reference is $requestId.</p>
        <p>The management</p>
        ";
        \App\Smtp::send( 
            $email, 
            'Your request has been approved', 
            $body
        );
    }


}