<?php
    /*Main application view template. Contains all dependencies and basic layout 
     */
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
    <link rel="stylesheet" href="<?=base_url('dist/luna/styles/styles.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>
    
    <script src="<?=base_url('dist/js/apps.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
</head>

    <?php echo isset($no_header) ? "<body class='blank'>" : "" ?>
    <div class="wrapper mainwrapper" data-url="<?=base_url()?>">
	
    <!-- toastr notification -->
    <?php if(isset($message)) {?>
    <script>toastr["<?=$notice?>"]("<?=$message?>")</script>
    <?php }
    
    !isset($no_header) ? $this->load->view('common/header_v') : "";
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
                    <div class="wBall" id="wBall_1">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" id="wBall_2">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" id="wBall_3">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" id="wBall_4">
                        <div class="wInnerBall">
                        </div>
                    </div>
                    <div class="wBall" id="wBall_5">
                        <div class="wInnerBall">
                        </div>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>
</body>
    
