<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='//fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' rel='stylesheet' type='text/css'>

    <title>Eve Trade Master</title>
    <link rel="stylesheet" href="<?=URL?>dist/luna/styles/styles.css?HASH_CACHE=<?=HASH_CACHE?>"/>
    <link rel="stylesheet" href="<?=URL?>dist/luna/styles/theme.min.css?HASH_CACHE=<?=HASH_CACHE?>"/>
    
    <script>var _404 = true;</script>
    <script src="<?=URL?>dist/js/apps.js?HASH_CACHE=<?=HASH_CACHE?>"></script>
</head>
<body class="blank">
    <div class="wrapper mainwrapper">
        <section class="content">
            <div class="back-link">
                <a onclick="window.history.back()" class="btn btn-accent go-back">Go back</a>
            </div>

            <div class="container-center md animated slideInDown">
                <div class="view-header">
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-close-circle"></i>
                    </div>
                    <div class="header-title">
                        <h3>404</h3>
                        <small>
                            Page Not Found
                        </small>
                    </div>
                </div>

                <div class="panel panel-filled">
                    <div class="panel-body">
                        Sorry, but the page you are looking for has not been found.
                    </div>
                </div>
                <div>
                    <a href="<?=URL?>main/login" class="btn btn-accent">Back to Login</a>
                </div>
            </div>
        </section>
    </div>
</body>
</html>

