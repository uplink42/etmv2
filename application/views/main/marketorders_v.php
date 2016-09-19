<script src="<?=base_url('assets/js/marketorders-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
                            <i class="pe page-header-icon pe-7s-cart">
                            </i>
                            <?php }?>
                    </div>
                    <div class="header-title">
                        <h1>
                            <?php echo $aggregate == 1 ? implode(' + ', $char_names) : $character_name ?>
                            's Market Orders
                        </h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-filled panel-c-success">

                    <div class="panel-heading">
                        
                        Market Orders from last <?=$interval?> days
                    </div>
                    <div class="panel-body">
           
                            <i class="fa fa-info yellow"></i> You can check if your orders are still on top of the list or have been undercut by clicking here
                
                    </div>
                </div> 
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="dropdown pull-right">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-interval" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Time Interval
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-interval">
                                <li><a href="<?=base_url('MarketOrders/index/'.$character_id.'/1?aggr='.$aggregate)?>">Last 24 hours</a></li>
                                <li><a href="<?=base_url('MarketOrders/index/'.$character_id.'/7?aggr='.$aggregate)?>">Last 7 days</a></li>
                                <li><a href="<?=base_url('MarketOrders/index/'.$character_id.'/14?aggr='.$aggregate)?>">Last 14 days</a></li>
                                <li><a href="<?=base_url('MarketOrders/index/'.$character_id.'/30?aggr='.$aggregate)?>">Last 30 days</a></li>
                                <li><a href="<?=base_url('MarketOrders/index/'.$character_id.'/60?aggr='.$aggregate)?>">Last 2 months</a></li>
                                <li><a href="<?=base_url('MarketOrders/index/'.$character_id.'/90?aggr='.$aggregate)?>">Last 3 months</a></li>
                                <li><a href="<?=base_url('MarketOrders/index/'.$character_id.'/365?aggr='.$aggregate)?>">Last 12 months</a></li>
                            </ul>
                        </div>
                        <div class="panel-tools">
                        </div>
                    </div>
                    <br>
                    <div class="panel-body transactions-body">
                        <p class="yellow"></p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="transactions-table">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Item</th>
                                        <th>Q</th>
                                        <th>ISK/unit</th>
                                        <th>ISK/total</th>
                                        <th>Type</th>
                                        <th>Other party</th>
                                        <th>Station</th>
                                        <th>Character</th>
                                        <th>State</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
