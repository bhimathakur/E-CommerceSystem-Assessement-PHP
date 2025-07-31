 **Technical Assessment - Backend systems for E-Commerce system**

Written in the PHP programming language, implemented the code for a E-Commerce system. Include core features such as user account login,  product catalog and inventory management,
 order tracking, and a CMS for content updates

**Description**

Written in the PHP Symfony Frameword programming language, implemented the code for a E-Commerce system. Include core features such as user account login,  product catalog and inventory management,
 order tracking, and a CMS for content updates
**Getting Started**

Dependencies
Apache Server (PHP version 8.2)
MySQL Server
Installing
Download code clone
Executing program

**Run below given command in root dir. as given in sequence:**

composer install

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load



**optional**
[
Open the .env file in root dir.
Replaces APP_ENV=dev with APP_ENV=prod
Then save file.
php bin/console clear:cache --env=prod
]

symfony server:start --port:8081


Now Open this link in your browser
http://localhost:8081/

**Login Credentials:**

Username: bhimathakur@gmail.com
Password: Admin@123
