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
    <link rel="stylesheet" href="<?=base_url('assets/luna/vendor/fontawesome/css/font-awesome.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/luna/vendor/animate.css/animate.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/luna/vendor/bootstrap/css/bootstrap.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/luna/vendor/toastr/toastr.min.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/luna/vendor/datatables/datatables.min.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>

    <!-- App styles -->
    <link rel="stylesheet" href="<?=base_url('assets/luna/styles/pe-icons/pe-icon-7-stroke.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/luna/styles/pe-icons/helper.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/luna/styles/stroke-icons/style.css')?>?HASH_CACHE=<?=HASH_CACHE?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/luna/styles/style.css')?>?HASH_CACHE=<?=HASH_CACHE?>">
    
    <!-- Vendor scripts -->
    <script src="<?=base_url('assets/luna/vendor/pacejs/pace.min.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/luna/vendor/jquery/dist/jquery.min.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/luna/vendor/bootstrap/js/bootstrap.min.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/luna/vendor/toastr/toastr.min.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/luna/vendor/sparkline/index.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/luna/vendor/flot/jquery.flot.min.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/luna/vendor/flot/jquery.flot.resize.min.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/luna/vendor/flot/jquery.flot.spline.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/luna/vendor/datatables/datatables.min.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/luna/vendor/jquery-ui/jquery-ui.min.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/luna/vendor/angular/angular.min.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>

    <!-- App scripts -->
    <script src="<?=base_url('assets/luna/scripts/luna.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/js/toastr_options.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    <script src="<?=base_url('assets/js/app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
    



</head>

    <?php echo isset($no_header) ? "<body class='blank'>" : "" ?>
    <div class="wrapper">
	
    <!-- toastr notification -->
    <?php if(isset($message)) {?>
    <script>toastr["<?=$notice?>"]("<?=$message?>")</script>
    <?php }
    
    !isset($no_header) ? $this->load->view('common/header_v') : "";
    $this->load->view($view);
    $this->load->view('common/footer_v');
?>
    </div>
    <?php $this->load->view('common/feedback_v'); ?>
</body>
