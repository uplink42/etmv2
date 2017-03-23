<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8">
    <meta name="description" content="eve trade master - web based profit tracker and asset manager for eve online">
    <meta name="author" content="nick starkey">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta property="og:title" content="Eve Trade Master" />
    <meta name="keywords" content="eve online trading market">
    <meta name="google-site-verification" content="AaRtrjK00fRsj5cWaYi3VnjiuOIpcRwZw4C860zpf9Y" />
    <link href='//fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' rel='stylesheet' type='text/css'>
    <!-- Page title -->
    <title>Eve Trade Master 2 - A web based Eve Online profit tracker, asset manager and trade analysis tool</title>

    <?php
        if (!empty($market)) {
            echo "<script>
                window.paceOptions = {
                    ajax: false
                };
            </script>";
        }
    ?>
    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?=base_url('dist/luna/styles/styles.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>
    <link rel="stylesheet" href="<?=base_url('dist/luna/styles/theme.min.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>
    <script src="<?=base_url('dist/js/apps.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>

    <?php
        if (!empty($market)) {
            echo "<script>
            $(document).ready(function() {
                $('body').addClass('pace-done');
            });
            </script>";
        }
    ?>
</head>
    <?php echo isset($no_header) ? "<body class='blank'>" : "" ?>
    <div class="wrapper mainwrapper" data-url="<?=base_url()?>">

    <!-- toastr notification -->
    <?php if (isset($message)) {?>
    <script>toastr["<?=$notice?>"]("<?=$message?>")</script>
    <?php }

    if (!empty($_SESSION['msg'])) {?>
    <script>toastr["<?=$this->session->flashdata('notice');?>"]("<?=$this->session->flashdata('msg');?>")</script>
    <?php }

    !isset($no_header) ? $this->load->view('common/header_v') : "";
?>
    
<?php
    $this->load->view($view);
    $this->load->view('common/footer_v');
?>
    </div>
    <?php if(!empty($email)) {$this->load->view('common/feedback_v');} ?>

        <div class="panel panel-filled panel-loading-common text-center">
            <div class="panel-body">
                Refreshing data... please wait
                <div class="windows8">
                    <br>
                    <div class="wBall" class="wBall_1">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" class="wBall_2">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" class="wBall_3">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" class="wBall_4">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" class="wBall_5">
                        <div class="wInnerBall">
                        </div>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>

        <div class="panel-loading-ajax">
            <div class="windows8">
                <br>
                <div class="wBall" class="wBall_1">
                    <div class="wInnerBall">
                    </div>
                </div>
                <div class="wBall" class="wBall_2">
                    <div class="wInnerBall">
                    </div>
                </div>
                <div class="wBall" class="wBall_3">
                    <div class="wInnerBall">
                    </div>
                </div>
                <div class="wBall" class="wBall_4">
                    <div class="wInnerBall">
                    </div>
                </div>
                <div class="wBall" class="wBall_5">
                    <div class="wInnerBall">
                    </div>
                </div>
            </div>
            <br>
            <br>
        </div>
        <?php include_once("analyticstracking.php") ?>
</body>
    

