<script src="<?=base_url('dist/js/apps/marketorders-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
                        <a href="<?=base_url('MarketOrders/index/'.$character_id.'?aggr='.$aggregate.'&check=1')?>">
                            <button class="btn btn-default btn-success pull-right btn-lg btn-check">Order Check</button>
                        </a>
                        Market Orders
                    </div>
                    <div class="panel-body">
                        <i class="fa fa-info yellow"></i> You can check the current state of your orders (undercut, expired or on top of the list) with Order Check at the right <br />
                        <i class="fa fa-info yellow"></i> There is a 6 minute cache timer between requests, so spamming this button only pushes you back in the waiting period.
                    </div>

                </div> 
            </div>
        </div>
        
        <div class="main-panel-orders">    
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-filled">
                        <div class="panel-heading">
                            <div class="panel panel-filled panel-c-success panel-collapse">
                                <div class="panel-heading">
                                    <h4><i class="pe-7s-cart"></i> Buy Orders</h4>
                                </div>
                            </div>
                            <button class="btn btn-default pull-right btn-clear">Clear filters</button>
                        </div>
                        <div class="panel-body buyorders-body">
                            <p class="yellow"></p>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="buyorders-table">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>Item</th>
                                            <th>Q</th>
                                            <th>ISK/unit</th>
                                            <th>ISK/total</th>
                                            <th>Station</th>
                                            <th>Character</th>
                                            <th>Range</th>
                                            <th>State</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($buyorders as $buy) {
                                            $url = $buy['url'];
                                            !empty($buy['status']) ? $status = $buy['status'] : $status = "-";
                                            $status == 'OK' ? $class = 'success' : ($status == 'undercut' ? $class = "danger" : $class = '');
                                            switch($buy['range']) {
                                                case '-1':
                                                $range = "Station";
                                                break;
                                                case '0':
                                                $range = "System";
                                                break;
                                                case '32767':
                                                $range = "Region";
                                                default:
                                                $range = $buy['range'] . " jumps";
                                            }
                                             ?>
                                            <tr class="<?=$class?>">
                                                <td><?=$buy['date']?></td>
                                                <td><img src="<?=$url?>" alt="icon"> <a class="item-name" style="color: #fff"><?=$buy['item_name'];?></a></td>
                                                <td><?=number_format($buy['vol'],0)?></td>
                                                <td><?=number_format($buy['price_unit'],2)?></td>
                                                <td><?=number_format($buy['price_total'],2)?></td>
                                                <td><?=$buy['station_name']?></td>
                                                <td><?=$buy['character']?></td>
                                                <td><?=$range?></td>
                                                <td><?=$status?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
         
                <div class="col-md-12">
                    <div class="panel panel-filled">
                        <div class="panel-heading">
                            <div class="panel panel-filled panel-c-success panel-collapse">
                                <div class="panel-heading">
                                    <h4><i class="pe-7s-cart"></i> Sell Orders</h4>
                                </div>
                            </div>
                            <button class="btn btn-default pull-right btn-clear">Clear filters</button>
                        </div>
                        <div class="panel-body sellorders-body">
                            <p class="yellow"></p> 
                            <div class="table-responsive ">
                                <table class="table table-striped table-hover" id="sellorders-table">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>Item</th>
                                            <th>Q</th>
                                            <th>ISK/unit</th>
                                            <th>ISK/total</th>
                                            <th>Station</th>
                                            <th>Character</th>
                                            <th>Range</th>
                                            <th>State</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($sellorders as $sell) {
                                            $url = $sell['url'];
                                            !empty($sell['status']) ? $status = $sell['status'] : $status = "-";
                                            $status == 'OK' ? $class = 'success' : ($status == 'undercut' ? $class = "danger" : $class = ''); 
                                            $range = "-";
                                            ?>
                                            <tr class="<?=$class?>">
                                                <td><?=$sell['date']?></td>
                                                <td><img src="<?=$url?>" alt="icon"><a class="item-name" style="color: #fff"> <?=$sell['item_name'];?></a></td>
                                                <td><?=number_format($sell['vol'],0)?></td>
                                                <td><?=number_format($sell['price_unit'],2)?></td>
                                                <td><?=number_format($sell['price_total'],2)?></td>
                                                <td><?=$sell['station_name']?></td>
                                                <td><?=$sell['character']?></td>
                                                <td><?=$range?></td>
                                                <td><?=$status?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        <?php $this->load->view('common/loader_v')?>
    </div>
</section>
