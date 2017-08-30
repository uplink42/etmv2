<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<head>
    <meta charset="utf-8">
    <title>Eve Trade Master 2 - A web based Eve Online profit tracker, asset manager and market tool</title>
    <meta name="keywords" content="eve online trading, trading tool, eve online isk, eve online market, eve online"/>    
    <meta name="description" content="A web based Eve Online profit tracker, asset manager and trading tool. Eve Trade Master is an extensive and easy to use set of tools to optimize your trading or learn the market">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Google Font -->
    <link href='//fonts.googleapis.com/css?family=Raleway:400,700' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
    
    <link rel="stylesheet" href="<?=base_url('dist/home/styles/css/styles.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>

    <?php $this->load->view('home/cookies'); ?>
    <!-- <link rel="manifest" href="manifest.json"> -->
    <script>
        //some default pre init
        var PB = PB || {};PB.q = PB.q || [];PB.events = PB.events || [];

        //PushBots ApplicationId (required)
        PB.app_id = "5969d3bb4a9efaa3b08b4568";
        //Your domain name, must be HTTPS or localhost  (required)
        PB.domain = "https://www.evetrademaster.com";
        PB.logging_enabled = true;
        PB.auto_subscribe = true;

        //Async functions
        //PB.q.push(["tag", ['test', "test3"]]);
        //PB.q.push(["alias", "username"]);
        //PB.q.push(["untag", ['test', "test3"]]);
        //Toggle notification subscription
        //PB.q.push(["subscribe", true]);

        //Pushbots events
        PB.events.push(["onRegistered", function(data){
            console.log("onRegistered" , data);
        }]);

        PB.events.push(["onRegisteredOnPushBots", function(data){
            console.log("onRegisteredOnPushBots", data);
        }]);
    </script>

    <script src="<?=base_url('sdk.min.js')?>" type="text/javascript" onload="PB.init()" async></script>
    <!-- Modernizr JS for IE9 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="../assets/plugins/modernizr.min.js"></script>
    <![endif]-->
</head>
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top" data-offset="51">

<?php $this->load->view('home/home_v'); ?>
<script src="<?=base_url('dist/home/js/apps.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<!-- <script src="sw-registration.js"></script> -->
</body>
    <?php include_once("analyticstracking.php") ?>
</html>