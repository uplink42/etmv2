#Eve Trade Master

a web based profit tracker, trading and asset manager tool for Eve Online.

#Main features:

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

#Requirements:
- Apache 2.2+
- PHP 7
- MariaDB or MySQL database
- Gulp task runner (requires NodeJS)
- Should work on any OS, but Windows users will need some extra tweaking with certificates to get the API calls working: 
[http://stackoverflow.com/questions/6400300/https-and-ssl3-get-server-certificatecertificate-verify-failed-ca-is-ok](http://stackoverflow.com/questions/6400300/https-and-ssl3-get-server-certificatecertificate-verify-failed-ca-is-ok)

#3rd party APIs:
- Eve XML API
- Eve CREST API
- Citadel data https://stop.hammerti.me.uk/api/ (unofficial)

#Dependencies (packages included):
- [PhealNG (XML API library)](https://github.com/3rdpartyeve/phealng)
- [CodeIgniter 3](https://github.com/bcit-ci/CodeIgniter)
- Fusioncharts
- Bootstrap 3
- jQuery 1.9 (and several jquery plugins like datatables and PACE)

#Installation
- Download/clone the repo onto your local web server.

1 - First update npm with the needed packages:

    npm update

2 - Then run gulp to generate the dist files:

    gulp

Gulp should now watch over and update any css/javascript files you change.

3 - Now you must import the seeded database (trader.sql) to bootstrap the application. Contains all relevant SDE data until 29/10/2016. This can be done like this:

    mysql -u <username> -p
    use trader
    source <path-to-file.sql>

4 - Create a folder with write permissions to store the API cache files

5 - Edit the following files:

    /application/config/config.php
    $config['base_url'] = 'http://localhost/etm_refactor/'; <- replace this with your ETM path (don't forget the trailing slash)

    /application/config/constants.php
    define("FILESTORAGE", "var/www/html/your_cache_folder"); <- replace this with the folder path you created earlier.

    /application/config/database.php
    'username' => 'root' <- replace this with your database user
    'password' => '' <- replace this with your database password
    'database' => '' <- replace this with the database name (trader by default)

And that should be all. You can now launch it and create an account.

#Crons:
There are several crons used to maintain the application. These can be found inside the /application/controllers/internal folder. They are not yet tested 100%.


#To-do:
- Documentation
- Rewrite api key storage and validation rules
- Rewrite css files into sass
- Fix the rare profit hanging bug...
- Restructure and normalize database
- Improve gulp tasks
- Port to Laravel (maybe)
- Automatic item list update cron from CREST (right now I have to manually update the database everytime new items are added into Eve)
- ??
