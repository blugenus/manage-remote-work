<?php
namespace App\Models;

class System extends \App\Model {

    /**
     * Creates all tables and default records as needed. 
     * 
     * @return void
     */
    public static function setup(){
        $sqls = [
            "
                CREATE TABLE `users` (
                  `userId` int(11) NOT NULL AUTO_INCREMENT,
                  `username` varchar(50) NOT NULL,
                  `password` varchar(255) NOT NULL,
                  `name` varchar(100) DEFAULT NULL,
                  `email` varchar(100) DEFAULT NULL,
                  `isEnabled` tinyint(1) DEFAULT '1',
                  `isAdmin` tinyint(1) DEFAULT '0',
                  PRIMARY KEY (`userId`),
                  UNIQUE KEY `username` (`username`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

            ",
            "
                INSERT INTO `users` (`username`, `password`, `name`, `email`, `isAdmin`, `isEnabled`) 
                VALUES ( 
                    'admin', 
                    '" . password_hash( '123', PASSWORD_BCRYPT, ['cost' => 12] ) ."',
                    'Administrator',
                    'account@example.com',
                    1,
                    1 
                );
            ",
            "
                CREATE TABLE `licenses` (
                  `licenseId` INT(11) NOT NULL AUTO_INCREMENT,
                  `productName` VARCHAR(100) DEFAULT '',
                  PRIMARY KEY (`licenseId`)
                ) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
            ",
            "
                INSERT INTO `licenses`(`licenseId`,`productName`) VALUES 
                (1,'Microsoft Office License'),
                (2,'Email Access Granted'),
                (3,'Git Repository Granted'),
                (4,'Jira Access Granted');            
            ",
            "
                CREATE TABLE `users_licenses` (
                  `userId` int(11) NOT NULL,
                  `licenseId` int(11) NOT NULL,
                  `isTicked` tinyint(1) DEFAULT '0',
                  PRIMARY KEY (`userId`,`licenseId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ",
            "
                CREATE TABLE `work_from_home` (
                  `requestId` int(11) NOT NULL AUTO_INCREMENT,
                  `userId` int(11) DEFAULT NULL,
                  `requestDate` date DEFAULT '1900-01-01',
                  `requestHours` int(11) DEFAULT '0',
                  `requestComment` text,
                  `statusId` tinyint(1) DEFAULT '0' COMMENT '0-pending, -1=declined, 1=approved, -2=canceled',
                  `adminUserId` int(11) DEFAULT '0',
                  `adminComment` text,
                  `createdDateTime` datetime DEFAULT '1900-01-01 00:00:00',
                  `closedDateTime` datetime DEFAULT '1900-01-01 00:00:00',
                  PRIMARY KEY (`requestId`),
                  KEY `statusId_requestDate` (`statusId`,`requestDate`),
                  KEY `userId_requestDate` (`userId`,`requestDate`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ",
            "
                CREATE TABLE `work_from_home_status` (
                  `statusId` int(11) NOT NULL,
                  `description` varchar(50) DEFAULT '',
                  PRIMARY KEY (`statusId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ",
            "
                INSERT INTO `work_from_home_status` (`statusId`, `description`) VALUES 
                ( -2, 'canceled' ),
                ( -1, 'reject' ),
                ( 0, 'pending' ),
                ( 1, 'approved' );
            "
        ];

        foreach( $sqls as $sql ){
            static::execute( $sql );
        }

    }


}