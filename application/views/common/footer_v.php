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
                <li><a href="<?=base_url()?>">Home</a></li>
                <?php if(isset($email)) { ?>
                <li><a data-toggle="modal" data-target="#modal-feedback">Feedback and bug reports</a></li>
                <?php } ?>
                <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=E92PVNRT3L9EQ" target="_blank">Donate</a></li>
                <li><a href="https://www.evetrademaster.com/blog" target="_blank">Blog</a></li>
            </ul>
        </div>
        <div class="panel-body text-center footer-desc" style="display: block;">
            © Eve Trade Master 2016 - design and development by uplink42<br>
            Eve Online, the Eve logo and all associated logos and designs are intellectual property of CCP hf,
            and are under copyright. That means copying them is not right. <br>
        </div>
    </div>
</div>