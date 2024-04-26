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
2. In the command window, type "CREATE DATABASE airportmanagement" and click Go.
3. Once you've done this, you should see a new schema on the left panel named "airportmanagement". Click it
4. Once you've done this, click "Import" at the top.
5. Under "File to Import", click "Choose File" and select the "airportmanagement.sql" file found in the SQL_Files directory of the application. Click Import at the bottom.
6. Congratulations! You have now set up the example database provided!

### Website setup

Now that you have the database setup, we can now setup the web interface.
Follow the steps below to setup the website:

1. Go to the directory where XAMPP was installed and go to the "htdocs" directory.
2. Place the Airport-Databse directory in here.
3. If you go to "localhost/Airport-Database/newMain.php", you will arrive at the homepage.
- **NOTE: htdocs is the root directory for localhost. If you place ALL files from the repo straight into htdocs, then you would instead enter "localhost/newMain.php".**
4. From here, the site should be working!

## Extra Notes

The BNA logo is not ours, and is used for the purposes of this project. All intellectual property belongs to the Nashville International Airport.

If you setup a database at a different location, changed the username, or changed the password, or have named the database something other than "airportmanagement", you will need to modify these parameters in "connectDatabase.php".
- $servername represents the IP of the server to connect to, $username represents the username of the database, $password represents the password, and $database represents the schema name.
