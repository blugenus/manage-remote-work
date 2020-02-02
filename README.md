# Stand-alone, browser-based app to manage remote work

## Assumptions

For the scope of the test I did not implement features that would obviiously be needed in a real system however these would be a repetition of other things already implement. These include:

- the ability to add more products to the licenses table.
- password change.
- additional data filtering, sorting and paging options.
- in a real system we wouldn't be giving send to the browser the trace information. 

## System Requirements

* PHP 7.x
* MySQL 5.7.x or greater

## Installation System

Please be aware that the following instructions are a get it up and ready quickly guide and I am in no way saying that any of them are good practices.

Assuming Debian 10 as host. 

```
sudo apt update
sudo apt upgrade
```

```
sudo apt install nginx php-fpm php-mysql mariadb-server mariadb-client git php7.1-xml php-mbstring -y
```

### Database MySQL/MariaDB

run the mysql secure installation 

```
sudo mysql_secure_installation
```

connect to the database from the cli

```
mysql -u root -p
```

Create a new database

```
MariaDB [(none)]> CREATE DATABASE newtestdb;
```

Create new user and assign privileges ( eg: 'username' and 'password' with full privileges on newtestdb.* )

```
MariaDB [(none)]> GRANT ALL PRIVILEGES ON newtestdb.* TO 'username'@'localhost' IDENTIFIED BY 'password';
```

```
MariaDB [(none)]> exit;
```

### Clone Repository

```
cd /var/www

git clone https://bitbucket.org/blugenus/test-remote-work-app.git

cd /var/www/test-remote-work-app/config/
```

fill in the database configuration details we set before:

```
sudo cp database-sample.json database.json

sudo nano database.json
```

```
{
    "host": "127.0.0.1",
    "username": "username",
    "password": "password",
    "database": "newtestdb",
    "port": 3306
}
```

and fill in the smtp configuration details we set before (ps please use the setting of your smtp server:

```
sudo cp smtp-sample.json smtp.json

sudo nano smtp.json
```

```
{
    "host": "127.0.0.1", 
    "requiresAuth": true,
    "fromEmail": "email", 
    "username": "username", 
    "password": "password", 
    "encryption": "tls",
    "port": 587
}
```

### Composer

Install composer

```
cd ~

curl -sS https://getcomposer.org/installer -o composer-setup.php

HASH="$(wget -q -O - https://composer.github.io/installer.sig)"

php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

get dependencies

```
cd /var/www/test-remote-work-app/

sudo composer install

```

### Nginx

```
cd /etc/nginx/sites-available/

sudo rm default

sudo nano test-remote-work-app

```

Paste the following configuration

```
server {
    listen       80;

    root /var/www/test-remote-work-app/public/;

    index index.php;

    location / {
        try_files $uri $uri/ /main.php?$query_string;
    }

    # PHP location:
    location ~ \.php$ {

        root /var/www/test-remote-work-app/App/;

        fastcgi_pass unix:/run/php/php7.3-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param   SCRIPT_FILENAME     $document_root$fastcgi_script_name;

    }

}
```

```
cd /etc/nginx/sites-enabled/

sudo rm default

sudo ln -s /etc/nginx/sites-available/test-remote-work-app /etc/nginx/sites-enabled/

sudo systemctl reload nginx

```

### Php-fpm

Should work out of the box :)

## System Setup

Launch your favorite browser and type http:// followed by the ip where you installed the system followed and /api/setup

example: http://127.0.0.1/api/setup

you should received back the following:

```
{
    "success": true
}
```

## Using the System

Launch your favorite browser and type http:// followed by the ip where you installed the system

example: http://127.0.0.1/

The username and password are 'admin' and '123' respectively.

Once you log in you are presented with your own Work from home requests. 

Administrators have the options to access the Employees and All the Work from Home requests.

In order to edit the licenses an employee has on need to access the employee details and clicn licenses

## Code Insights

### Directory structure

```
├── App                     # back-end php code.
|   ├ Controllers           #
|   ├ Exceptions            #
|   ├ Models                #
|   ├ ...                   # core classes including base model and controller, error handler, database etc.
|   └ main.php              # init file for the back end.
├── config                  # configuration files.
|   ├ database-sample.json  # copy this to database.json and fill in the required values to connect to your MySQL/mariaDB database.
|   └ smtp-sample.json      # copy this to smtp.json and fill in the required values to connect to your smtp server.
├── public                  # front end code.
|   ├ app                   # 
|   | ├ api.*               # manage the comunication with the api.
|   | ├ page.*              # the user page and modal views.
|   | ├ page.js             # the page/modal manager.
|   | ├ navigation.js       # handles app navigation.
|   | ├ http.js             # core classes including base model and controller, error handler, database etc.
|   | ├ security.js         # handles basic authentication.
|   | ├ template.js         # loads and caches html template.
|   | └ core.js             # main file for the front end app.
|   ├ css                   # contains bootstrap-4.3.1.min.css and main.css.
|   ├ images                # contains the background image.
|   ├ js                    # contains third party libraries and main.js
|   ├ templates             # Cointains all the html templates used bu the app.
|   └ index.htm             # our sigle html document. 
├── test                    # PHPUnit Tests
├── composer.json
└── README.md
```

### Back End

The only libraries used are fast-route 1.3, phpmailer 6.1 and PHPUnit 8.

As mentioned above the app starts in main.php from the it is self explanatory.

Known Issues:
- Needs a call to properly check for duplicate username when creating a new user. 
- The update user licenses need validation.

### Front End

The only libraries used are Bootstrap 4.3.1 and jQuery 3.4.1

No building tools where use as was there was no mention of them in the test brief.

In index.htm there are 4 places of interest. 
1. the head area where all the js files are included ( again no build tools ).
2. the navbar which is used from navigation.js to hide and show links depending on the security level. 
3. the div with id="idPageContainer" - all pages are loaded here.
4. the div with id="idModalContainer" - all modals are loaded here.

In main.js: 
```
$(document).ready(function(){
    $('.header').height($(window).height()); // match screen height; ... 
    system.init(); // initialize app.
});
```

Each of the javascript files api.* bind themselfs to the modules.api object.

On the other hand the javascript files page.* register themselfts with the page and modal handler page.js. They also instruct template.js to download the required dependacy.

Known Issues:
- Needs better handling in case of errors


### Running PHPUnit tests
```
cd /var/www/test-remote-work-app/

./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```

## API Quick Reference

When there is an issue the api will reply with one of these Status Code 400, 401, 405, 500 and the error object.

The error object is a simple json object like the following
```
{
    "class": "...",
    "message": "...",
    "trace": "...",
    "file": "...",
    "line": ...
}
```

### System Setup

Request:
GET /api/setup

Response on success:
```
{
    "success": true
}
```

### Login

Request:
POST /api/login
```
{
    "username":"admin",
    "password":"123"
}
```

Response on success:
```
{
    "success": true,
    "user": {
        "userId": 1,
        "username": "admin",
        "isAdmin": 1
    }
}
```

Response on failure:
{
    "success": false
}

### Logout

Request:
GET /api/logout

Response on success:
```
{
    "success": true
}
```

### get Current Loggedin User

Request:
GET /api/current-user

Response on success:
```
{
    "success": true,
    "user": {
        "userId": 1,
        "username": "admin",
        "isAdmin": true
    }
}
```

Response on failure:
```
{
    "success": false
}
```

### Employee's own Work from home requests

Request:
GET /api/user/work-from-home

Response on success:
```
{
    "success": true,
    "records": [
        {
            "requestId": 1,
            "userId": 1,
            "requestDate": "2020-02-05",
            "requestHours": 3,
            "requestComment": "",
            "statusId": 0,
            "adminUserId": 0,
            "adminComment": null,
            "createdDateTime": "2020-02-02 21:05:34",
            "closedDateTime": "1900-01-01 00:00:00",
            "status": "pending",
            "employee": "Administrator",
            "admin": ""
        }
    ]
}
```

### Employee create Work from home requests

Request: 
POST /api/user/work-from-home
```
{
    "date": "2020-02-05",
    "hours": 3,
    "comment": ""
}
```

Response on success:
```
{
    "success": true,
    "insertId": 1
}
```

### Employee cancel Work from home requests

Request:
PATCH /api/user/work-from-home/:requestId/cancel

Response on success:
```
{
    "success": true
}
```

### Administrator all employees' Work from home requests 

Request:
GET /api/users/work-from-home

Response on success:
```
{
    "success": true
}
```

### Administrator rejecting an employee's Work from home request 

Request:
PATCH /api/users/work-from-home/:requestId/decline
```
{
    "comment": "..."
}
```

Response on success:
```
{
    "success": true
}
```

### Administrator approves an employee's Work from home request

Request:
PATCH /api/users/work-from-home/:requestId/approve
```
{
    "comment": "..."
}
```

Response on success:
```
{
    "success": true
}
```

### Administrator lists all employees

Request:
GET /api/users

Response on success:
```
{
    "success": true,
    "records": [
        {
            "userId": 1,
            "username": "admin",
            "name": "Administrator",
            "email": "account@example.com",
            "isAdmin": 1,
            "isEnabled": 1
        }
    ]
}
```

### Administrator create a new employees

Request:
POST /api/users
```
{
    "username": "...",
    "name": "...",
    "email": "...",
    "isAdmin": ...
}
```

Response on success:
```
{
    "success": true,
    "userId": 2
}
```

### Administrator update employees details

Request:
PUT /api/users/:userId
```
{
    "username": "...",
    "name": "...",
    "email": "...",
    "isAdmin": ...,
    "isEnabled": ...
}
```

Response on success:
```
{
    "success": true
}
```

### Administrator lists an employee's licenses

Request:
GET /api/users/:userId/licenses

Response on success:
```
{
    "success": true,
    "records": [
        {
            "licenseId": 1,
            "productName": "Microsoft Office License",
            "isTicked": 0
        },
        {
            "licenseId": 2,
            "productName": "Email Access Granted",
            "isTicked": 0
        },
        {
            "licenseId": 3,
            "productName": "Git Repository Granted",
            "isTicked": 0
        },
        {
            "licenseId": 4,
            "productName": "Jira Access Granted",
            "isTicked": 0
        }
    ]
}
```

### Administrator updates the employee's licenses

Request:
PUT /api/users/:userId/licenses
```
[
    {
        "licenseId": 1,
        "isTicked": 1
    },
    {
        "licenseId": 2,
        "isTicked": 0
    },
    {
        "licenseId": 3,
        "isTicked": 0
    },
    {
        "licenseId": 4,
        "isTicked": 0
    }
]
```

Response on success:
```
{
    "success": true
}
```
