# Airport Management Database Web Application

Authors: Alec Creasy, Matthew Clay

## Overview

This is a web application created for our Database Management Systems course at MTSU (CSCI 4560/5560).
The web application is a proof of concept for a database for an airport (BNA here) to manage its airlines, passengers, staff, and flights scheduled.
The application utilizes HTML, CSS, and JavaScript for it's front-end design, and uses PHP and MySQL for the back-end.

## How To Run It

### XAMPP

Any web-server that is used with MySQL will work, but for our purposes, we used XAMPP.
XAMPP is a free, open-source web development server solution containing Apache, an open-source HTTP web server and MySQL, an open-source relational database management system.
To download XAMPP, visit https://www.apachefriends.org/

Once downloaded, start the Apache and MySQL servers.
- **NOTE: If you already have an instance of Apache or MySQL running, you will need to disable them to run them through the XAMPP Control Panel!**

### Database Setup

Once you have a xampp server running on your local machine, open a web browser and type the URL "localhost/phpmyadmin/"
This will take you to phpMyAdmin, a free, open-source tool for managing MySQL and MariaDB databases.

Once you are on the phpMyAdmin homepage, follow the following steps:

1. Click "SQL" at the top
2. In the command window, type the following command and click Go.
 ```
 CREATE DATABASE airportmanagement
 ```
4. Once you've done this, you should see a new schema on the left panel named "airportmanagement". Click it
5. Once you've done this, click "Import" at the top.
6. Under "File to Import", click "Choose File" and select the "airportmanagement.sql" file found in the SQL_Files directory of the application. Click Import at the bottom.
7. Congratulations! You have now set up the example database provided!

### Website setup

Now that you have the database setup, we can now setup the web interface.
Follow the steps below to setup the website:

1. Go to the directory where XAMPP was installed and go to the "htdocs" directory.
2. Place the Airport-Databse directory in here.
3. If you go to "localhost/Airport-Database/newMain.php", you will arrive at the homepage.
- **NOTE: htdocs is the root directory for localhost. If you place ALL files from the repo straight into htdocs, then you would instead enter "localhost/newMain.php".**
4. From here, the site should be working!

### Administrator Settings

Included is an administrator named "superadmin" that is found in the "admin" table of the "airportmanagement" schema. To add more admins, you will need to insert new ones in phpMyAdmin.
To do this, follow these steps:

1. Go to localhost/phpMyAdmin/
2. Click the "airportmanagement" schema on the left.
3. Click "SQL" at the top
4. Run the following command:
```
INSERT INTO admin VALUES ("username-here", "password-here")
```
- Replace "username-here" and "password-here" with the username and password combination you need!
- **NOTE: If you remove the quotation marks (""), the command will not run and you will receive an error!**

## Extra Notes

The BNA logo is not ours, and is used for the purposes of this project. All intellectual property belongs to the Nashville International Airport.

If you setup a database at a different location, changed the username, changed the password, or have named the database something other than "airportmanagement", you will need to modify these parameters in "connectDatabase.php".
- $servername represents the IP of the server to connect to, $username represents the username of the database, $password represents the password, and $database represents the schema name.
