<script src="<?=base_url('dist/js/apps/citadeltax-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <?php $this->load->view('common/selector_v');?>
                    <div class="header-icon">
                        <?php if ($aggregate == 0) {?>
                        <img alt="character portrait" class="character-portrait" src="https://image.eveonline.com/Character/<?=$character_id?>_64.jpg">
                            <?php } else {
    ?>
                            <i class="pe page-header-icon pe-7s-link">
                            </i>
                            <?php }?>
                    </div>
                    <div class="header-title">
                        <h1>
                            <?php echo $aggregate == 1 ? implode(' + ', $char_names) : $character_name ?>
                            's Citadel Taxes
                        </h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-filled panel-c-success">
                    <div class="panel-body">
                        <i class="fa fa-info yellow"></i> Here you can set custom broker fees for transactions on certain Citadels of your choice<br />
                    </div>
                </div> 
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-body tax-list-panel">
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row add-list-item">
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-body tax-panel">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h4><i class="fa fa-arrow-up"></i>  <span class="yellow contents"></span></h4>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>


        
        
    </div>
</section>
