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
                            <?php } else { ?>
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
                <div class="panel panel-filled panel-c-success panel-main">
                    <div class="panel-body">
                        <i class="fa fa-info yellow"></i> Here you can set or unset custom broker fees for transactions on certain Citadels of your choice<br />
                        <i class="fa fa-info yellow"></i> Setting a different tax for an existing entry will update it
                        <?php if ($aggregate == 1) { ?>
                        <br />
                        <i class="fa fa-info yellow"></i> You must be outside aggregated mode to use this page. Select any of your characters at the top right.
                        <?php } ?>
                    </div>
                </div> 
            </div>
        </div>
    
        <?php if ($aggregate == 0) { ?>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h5><i class="fa fa-usd"></i> Assign a Custom Tax</h5>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body tax-creation-panel">
                        <form class="form-horizontal add-tax" data-url="<?=base_url()?>" method="POST">
                            <div class="form-group">
                                <label for="citadel" class="col-sm-2 control-label">Citadel</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control origin-station" id="citadel" name="citadel" placeholder="Begin typing and select one of the highlighted stations" autofocus required>
                                </div>
                                <label for="tax" class="col-sm-1 control-label">Tax</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="tax" name="tax" pattern="^(0(\.\d+)?|1(\.0+)?)$" title="Must insert a decimal value (example: 0.01 for 1%" required> 
                                </div>
                            </div>  
                            <input type="hidden" value="<?=$character_id?>" name="character" class="characterid"> 
                            <div class="text-center"><i class="fa fa-info"></i> Broker fee must be inserted as a decimal (e.g 0.05 represents 5%)</div>
                            <button type="submit" class="btn btn-default submit-tax">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel panel-filled panel-c-success panel-collapse">
                            <div class="panel-heading">
                                <h5><i class="fa fa-usd"></i> Existing entries</h5>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body tax-list">
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered table-hover table-stripped">
                                <thead>
                                    <tr>
                                        <th>Citadel</th>
                                        <th style="width:20%">Broker fee</th>
                                        <th style="width:20%">Remove</th>
                                    </tr>
                                </thead>
                                <tbody class="tax-entries">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>    
    </div>
</section>
