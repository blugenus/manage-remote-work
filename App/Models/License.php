<?php
namespace App\Models;

class License extends \App\Model {

    /**
     * update a user's licenses
     * 
     * @param Int $userId  The user Id whom licenses are to be updated
     * @param Array $licenses  An array of object with 2 keys, licenseId, isTicked.
     * 
     * @return Boolean
     */
    public static function setUserLicenses( Int $userId, Array $licenses ){
        $bindings = '';
        $values = [];
        $placeHolders = [];
        foreach( $licenses as &$license ){
            $bindings .= 'iii';
            $values[] = &$userId;
            $values[] = &$license['licenseId'];
            $values[] = &$license['isTicked'];
            $placeHolders[] = '(?,?,?)';
        }

        $result = static::bindAndQuery( 
            "INSERT INTO `users_licenses` ( `userId`, `licenseId`, `isTicked` ) VALUES " . implode( ',', $placeHolders ) . " ON DUPLICATE KEY UPDATE `isTicked` = VALUES(`isTicked`);",
            $bindings,
            $values
        );
        return true;
    }

    /**
     * return the licenses of a particular user.
     * 
     * @param Int $userId  The user Id whom licenses are to be returns
     * 
     * @return Array
     */
    public static function getUserLicenses( $userId ){
        $result = static::bindAndQuery( 
            "
            SELECT 
                `a`.`licenseId`,
                `a`.`productName`,
                IFNULL( `b`.`isTicked`, 0 ) `isTicked`
            FROM
                `licenses` `a`
            LEFT JOIN 
                (SELECT * FROM `users_licenses` WHERE `userId` = ?) `b`
            ON `a`.`licenseId` = `b`.`licenseId`
            ",
            "i",
            [ &$userId ]
        );
        return $result['records'];
    }


}