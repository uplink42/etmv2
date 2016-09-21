<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' rel='stylesheet' type='text/css'>

    <!-- Page title -->
    <title>Eve Trade Master</title>

    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?=URL?>assets/luna/vendor/fontawesome/css/font-awesome.css"/>
    <link rel="stylesheet" href="<?=URL?>assets/luna/vendor/animate.css/animate.css"/>
    <link rel="stylesheet" href="<?=URL?>assets/luna/vendor/bootstrap/css/bootstrap.css"/>
    <link rel="stylesheet" href="<?=URL?>assets/luna/vendor/toastr/toastr.min.css"/>

    <!-- App styles -->
    <link rel="stylesheet" href="<?=URL?>assets/luna/styles/pe-icons/pe-icon-7-stroke.css"/>
    <link rel="stylesheet" href="<?=URL?>assets/luna/styles/pe-icons/helper.css"/>
    <link rel="stylesheet" href="<?=URL?>assets/luna/styles/stroke-icons/style.css"/>
    <link rel="stylesheet" href="<?=URL?>assets/luna/styles/style.css">
    
    <!-- Vendor scripts -->
    <script src="<?=URL?>assets/luna/vendor/pacejs/pace.min.js"></script>
    <script src="<?=URL?>assets/luna/vendor/jquery/dist/jquery.min.js"></script>
    <script src="<?=URL?>assets/luna/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=URL?>assets/luna/vendor/toastr/toastr.min.js"></script>
    <script src="<?=URL?>assets/luna/vendor/sparkline/index.js"></script>
    <script src="<?=URL?>assets/luna/vendor/flot/jquery.flot.min.js"></script>
    <script src="<?=URL?>assets/luna/vendor/flot/jquery.flot.resize.min.js"></script>
    <script src="<?=URL?>assets/luna/vendor/flot/jquery.flot.spline.js"></script>

    <!-- App scripts -->
    <script src="<?=URL?>assets/luna/scripts/luna.js"></script>
    <script src="<?=URL?>assets/js/toastr_options.js"></script>
</head>
<body class="blank">

<!-- Wrapper-->
<div class="wrapper">


    <!-- Main content-->
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
                    Sorry, but the page you are looking for has note been found. 
		    Try checking the URL for error, then hit the refresh button on your browser 
		    or try find something else.

                </div>
            </div>
            <div>
                <a href="<?=URL?>Main/Login" class="btn btn-accent">Back to Login</a>
            </div>

        </div>
    </section>
    <!-- End main content-->
</div>
<!-- End wrapper-->
</body>
</html>