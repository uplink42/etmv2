<h1>Eve Trade Master</h1>

a web based profit tracker, trading and asset manager tool for Eve Online (BETA native client for windows and android available)

Main features:

- tracks everything you buy and sell and calculates profits made (even if you buy and sell things over different characters)
- calculates taxes based on character standings
- calculates statistics for your best items, best time zones, fastest sellers and what's causing losses, etc (email reporting is also available)
- can plot your overall progress, asset distribution and wealth acquisition with several charts
- can check your market orders in real-time using CREST to see if they have been outbid or not
- displays everything you need to know from transactions, contracts, asset distribution and evaluation
- can simulate trades between different stations or regions, taking your standings and character skills into account using live CREST data
- can create stock lists up to 100 items, simulate profits with the above method and display best margins, best profit/m3, etc
- updates your data daily even if you don't login (for e-mail reports and more accurate net-worth evolution charts)
- can set custom broker fees for citadels of your choosing until ccp gives us proper crest support
- new! browse the market in real time trough all regions in New Eden
- new! now available as native client for windows and android

<h1>Requirements:</h1>

- Apache 2.2+ web server
- PHP 7.1
- MariaDB or MySQL database
- NodeJS (for npm)
- Should work on any OS, but Windows users will need some extra tweaking with certificates to get the API calls working: 
[http://stackoverflow.com/questions/6400300/https-and-ssl3-get-server-certificatecertificate-verify-failed-ca-is-ok](http://stackoverflow.com/questions/6400300/https-and-ssl3-get-server-certificatecertificate-verify-failed-ca-is-ok)


<h1>Dependencies (packages included):</h1>

You will also need:
- npm
- bower
- composer

<h1>3rd party APIs:</h1>

- Eve XML API
- Eve CREST API
- Citadel data https://stop.hammerti.me.uk/api/ (unofficial)


<h1>Installation</h1>

- Download/clone the repo onto your local web server.

1 - First update npm with the needed packages:

    npm update

2 - Then install bower and gulp if needed
    
    npm install bower
    npm install gulp
    
 and run
 
     bower update
     composer update
     gulp
     
3 - Import the database (schema.sql) 

4 - Make sure /phealcache has write permissions

5 - Edit the following files:

    /application/config/config.php
    $config['base_url'] = ''; //your ETM path (don't forget the trailing slash)

    /application/config/database.php
    $db['default']
    'username' => 'root' // db user
    'password' => ''     // db password
    'database' => ''     // db name
    
6- Import the all_seeders sql file in /seeders

And that should be all. You can now launch it and create an account.

<h1>Crons:</h1>

    00 14 * * * cd [base application path] && php index.php internal/Autoexec_outposts
    30 09 * * * cd [base application path] && php index.php internal/Autoexec_pricedata
    55 23 * * * cd [base application path] && php index.php internal/Async_updater
    
<h1>Unit Tests</h1>

First create an identical database and configure it on /config/database.php. Change the application enrvironment to 'testing' in index.php and browse to /ciunit_controller in your browser. You must run the test seeder file (in the correct database!) before every test run. You can write unit tests in /application/tests.


<h1>To-do:</h1>

- Rewrite api key storage and validation rules
- Automatic item list update cron from CREST
- Unit tests (lots of them)

