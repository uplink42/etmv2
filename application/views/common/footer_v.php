<?php 
    if(!isset($no_header)){
    ?>
    <div class="col-lg-4 col-lg-offset-4 col-sm-5 col-sm-offset-5 col-xs-12 col-xs-offset-0">
<?php    
    }
    else {
    ?>
    <div class="col-lg-6 col-lg-offset-3 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0">
<?php
    }
?>
    <div class="panel panel-filled panel-c-warning footer-panel">
        <div class="panel-heading text-center footer-links">
            <div class="panel-tools">
                <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                <a class="panel-close"><i class="fa fa-times"></i></a>
            </div>
            <ul class="list-inline">
                <li>Home</li>
                <li><a data-toggle="modal" data-target="#modal-feedback">Feedback and bug reports</a></li>
                <li>Donate</li>
                <li>Blog</li>
            </ul>
        </div>
        <div class="panel-body text-center footer-desc" style="display: block;">
            © Eve Trade Master 2016 - design and development by uplink42<br>
            Eve Online, the Eve logo and all associated logos and designs are intellectual property of CCP hf,
            and are under copyright. That means copying them is not right. <br>
        </div>
    </div>


</div>