#Eve Trade Master

a web based profit tracker, trading and asset manager tool for Eve Online (native client for windows and android available)

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

#Requirements:
- Apache 2.2+ web server
- PHP 7.1
- MariaDB or MySQL database
- Gulp task runner (requires NodeJS)
- Should work on any OS, but Windows users will need some extra tweaking with certificates to get the API calls working: 
[http://stackoverflow.com/questions/6400300/https-and-ssl3-get-server-certificatecertificate-verify-failed-ca-is-ok](http://stackoverflow.com/questions/6400300/https-and-ssl3-get-server-certificatecertificate-verify-failed-ca-is-ok)

#Packet managers required:
- bower
- npm
- composer

#Dependencies (packages included):
- AngularJS 1.6
- [PhealNG (XML API library)](https://github.com/3rdpartyeve/phealng)
- [CodeIgniter 3](https://github.com/bcit-ci/CodeIgniter)
- Fusioncharts
- Twig

#3rd party APIs:
- Eve XML API
- Eve CREST API
- Citadel data https://stop.hammerti.me.uk/api/ (unofficial)


#Installation
- Download/clone the repo onto your local web server.

1 - First update npm with the needed packages:

    npm update

2 - Then install (if needed) and run gulp 
    
    npm install gulp
    gulp
    
 2.1 - Browse to marketexplorer/index and run
    
    npm install bower
    bower update
    npm update
    gulp

3 - Import the database (schema.sql) 

4 - Make sure /phealcache has write permissions

5 - Edit the following files:

    /application/config/config.php
    $config['base_url'] = ''; //your ETM path (don't forget the trailing slash)

    /application/config/database.php
    'username' => 'root' <- replace this with your database user
    'password' => '' <- replace this with your database password
    'database' => '' <- replace this with the database name
    
6- Import the sql files in /seeders in this order:
    
    calendar_seeder
    item_seeder
    region_seeder
    system_seeder
    faction_seeder
    corporation_seeder
    station_seeder
    fixed_prices_seeder
    ship_volumes_seeder
    item_price_data_seeder

7- Run composer

    composer update
    
And that should be all. You can now launch it and create an account.

#Crons:

    00 14 * * * cd [base application path] && php index.php internal/Autoexec_outposts
    30 09 * * * cd [base application path] && php index.php internal/Autoexec_pricedata
    55 23 * * * cd [base application path] && php index.php internal/Async_updater
    
#Unit Tests

First create an identical database and configure it on /config/database.php. Change the application enrvironment to 'testing' in index.php and browse to /ciunit_controller in your browser. You must run the test seeder file (in the correct database!) before every test run. You can write unit tests in /application/tests.


#To-do:
- Rewrite api key storage and validation rules
- Automatic item list update cron from CREST

