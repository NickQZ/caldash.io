# CalDash.io
## BIT701 Assessment 3

CalDash.io is a responsive web-based nutrition tracking application developed as part of the BIT701 IT Project.

The application allows users to:

- Register and log in
- Record meals and food consumption
- Search and select foods
- Enter food quantities or servings
- View nutritional information
- Monitor daily calories, protein, carbohydrates and fat
- Review their meal history
- Edit and delete meal entries

The application was developed as a local web application using PHP, MySQL, HTML, CSS, JavaScript and Bootstrap.

## 1. System Requirements

The following software is required to run CalDash locally:

- Windows operating system
- WAMP Server
- Apache 2.4.65 or compatible version
- PHP 8.3 or compatible version
- MySQL
- phpMyAdmin
- Modern web browser such as:
- Google Chrome
- Microsoft Edge
- Mozilla Firefox

CalDash was developed and tested using WAMP Server on Windows.

## 2. Technologies Used
- Front-End
- HTML5
- CSS3
- JavaScript
- Bootstrap
- Back-End
- PHP 8.3.28
- Apache 2.4.65
- Database
- MySQL
- phpMyAdmin
- Development Tools
- Visual Studio Code
- WAMP Server
- Git / GitHub
- Excalidraw for database and ERD design

## 3. Included Artefacts

The submission contains the following project artefacts.

- Application Code
- PHP application files
- HTML markup
- CSS files
- JavaScript files
- Images and other assets
- PHP include files
- Database configuration files
- Database
- SQL database schema
- Database relationships
- Initial/sample food data
- Database setup script
- Documentation
- Project documentation
- Entity Relationship Diagram (ERD)
- Testing documentation
- Weekly project journals
- Final reflection
- Other assessment artefacts required for BIT701 Assessment 3
- README

This README provides instructions for installing, configuring and running CalDash locally.

## 4. Project Structure

The main application is structured as follows:

- caldash/

    - index.php
    - login.php
    - register.php
    - dashboard.php
      
- assets/
-  css/
-  js/
-  includes/
-  config/
    -  database.php
- database/
   - caldash.sql

The database/caldash.sql file contains the SQL required to create the CalDash database structure and populate the initial food data.

## 5. Installation
Step 1 — Install and Start WAMP

Install WAMP Server on the development computer.

Start WAMP Server and ensure that the WAMP icon is green.

A green WAMP icon indicates that the required Apache and MySQL services are running.

Step 2 — Copy the CalDash Application

Copy the caldash application folder into the WAMP web directory.

For a standard WAMP installation, this will be:

C:\wamp64\www\caldash\

The main application file should therefore be located at:

C:\wamp64\www\caldash\index.php

Step 3 — Import the Database

Open phpMyAdmin in a web browser:

http://localhost/phpmyadmin/

In phpMyAdmin:

Select the Import tab.
Click Choose File.
Select:
database/caldash.sql
Click Go.
Wait for the import to complete.
Confirm that the caldash database and required tables have been created.

The SQL script contains the database structure, relationships and initial/sample food data.

Note: If the SQL script creates the caldash database automatically, there is no need to manually create the database before importing the SQL file.

Step 4 — Configure the Database Connection

Open:

caldash/config/database.php

The database connection should contain the appropriate local MySQL credentials.

For a standard WAMP installation, the connection may use:

$host = "localhost";
$username = "root";
$password = "";
$database = "caldash";

The database username and password may need to be changed depending on the local MySQL configuration.

If a dedicated MySQL application user is configured, use those credentials instead of the root account.

## 6. Running CalDash

After completing the installation steps:

Start WAMP Server.
Confirm that the WAMP icon is green.
Confirm that Apache and MySQL are running.
Confirm that the CalDash database has been imported.
Confirm that the database credentials in config/database.php are correct.
Open a modern web browser.

Navigate to:

http://localhost/caldash/

The CalDash home page should load.

From the home page, a new user can register for an account and then log in to access the application.

## 7. Using the Application

After logging in, the main MVP functionality can be tested through the following process:

Register a new user account.
Log in using the registered account.
Open the meal logging functionality.
Select a meal type such as Breakfast, Lunch, Dinner or Snack.
Search for a food.
Select a food from the available results.
Enter the quantity or serving amount.
Save the meal entry.
View the nutritional information on the dashboard.
Open meal history to view previously recorded meals.
Edit or delete an existing meal entry.

The dashboard displays the user's daily nutritional intake, including:

Calories
Protein
Carbohydrates
Fat

## 8. Database Configuration

CalDash uses MySQL to store application data.

The database setup script is located at:

database/caldash.sql

The application database connection settings are located at:

config/database.php

The SQL setup script creates the CalDash database structure, including the required tables and relationships, and inserts the initial/sample food data.

Main Database Components
Users

Stores registered user account information.

Foods

Stores food information including:

Food name
Calories
Protein
Carbohydrates
Fat
Serving size
Meal Entries

Stores food consumed by users, including:

User
Food
Meal type
Quantity
Date/time logged

Security: Real passwords or sensitive database credentials should not be included in publicly accessible source control repositories.

## 9. Troubleshooting
CalDash Does Not Load

Check that:

WAMP Server is running.
The WAMP icon is green.
Apache is running.
The CalDash folder is located inside the WAMP www directory.
index.php exists in the CalDash root directory.
The browser is accessing:
http://localhost/caldash/
Database Connection Error

Check that:

MySQL is running.
The caldash database exists in phpMyAdmin.
The database username is correct.
The database password is correct.
The database name is caldash.
config/database.php contains the correct connection details.
The configured database user has appropriate privileges.
Page Shows a PHP Error

Check that:

PHP is installed and enabled in WAMP.
The PHP version is compatible with the application.
The project files have been copied correctly.
PHP file paths are correct.
Required include files exist.
config/database.php is correctly configured.
Database Import Error

Check that:

MySQL is running.
The correct database/caldash.sql file has been selected.
The SQL file has not been modified incorrectly.
The database user has permission to create the required database and tables.

## 10. Development Environment

CalDash was developed and tested using the following environment:

Component	Version / Environment
Operating System	Windows
Web Server	Apache 2.4.65
PHP	8.3.28
Database	MySQL
Database Management	phpMyAdmin
Development Environment	WAMP Server
Code Editor	Visual Studio Code

The application was designed to operate as a local web application for the purposes of the BIT701 IT Project.

## 11. Project Scope
Included in the MVP

The CalDash MVP includes:

User registration
User login and logout
Meal logging
Breakfast, lunch, dinner and snack categories
Food searching
Food selection
Quantity/serving entry
Nutritional information tracking
Daily calorie tracking
Daily protein tracking
Daily carbohydrate tracking
Daily fat tracking
Meal history
Editing meals
Deleting meals
Responsive web interface
Outside the Scope of the MVP

The following features were intentionally excluded from the MVP:

Barcode scanning
AI meal recommendations
Social networking
Recipe generation
Advanced analytics
Native Android/iOS applications
External hosting
Personal nutritional goal tracking

These features were excluded to maintain an achievable project scope and allow the core CalDash functionality to be developed and tested within the available project timeframe.

## 12. Project Completion

Once the installation and configuration steps have been completed, CalDash can be accessed locally through:

http://localhost/caldash/

## The application is intended to demonstrate the completed MVP functionality developed for the BIT701 Assessment 3 project.
