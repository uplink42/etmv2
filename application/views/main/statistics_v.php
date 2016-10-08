<?php
include_once 'assets/fusioncharts/php-wrapper/fusioncharts.php';
?>
<!--Fusioncharts -->
<script src="<?=base_url('assets/fusioncharts/js/fusioncharts.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<script src="<?=base_url('dist/js/apps/statistics-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
                            <i class="pe page-header-icon pe-7s-graph1">
                            </i>
                            <?php }?>
                    </div>
                    <div class="header-title">
                        <h1>
                            <?php echo $aggregate == 1 ? implode(' + ', $char_names) : $character_name ?>
                            's Statistics
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
                        <div class="dropdown pull-right">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-interval" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Time Interval
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-interval">
                                <li><a href="<?=base_url('Statistics/index/'.$character_id.'/1?aggr='.$aggregate)?>">Last 24 hours</a></li>
                                <li><a href="<?=base_url('Statistics/index/'.$character_id.'/7?aggr='.$aggregate)?>">Last 7 days</a></li>
                                <li><a href="<?=base_url('Statistics/index/'.$character_id.'/14?aggr='.$aggregate)?>">Last 14 days</a></li>
                                <li><a href="<?=base_url('Statistics/index/'.$character_id.'/30?aggr='.$aggregate)?>">Last 30 days</a></li>
                                <li><a href="<?=base_url('Statistics/index/'.$character_id.'/60?aggr='.$aggregate)?>">Last 2 months</a></li>
                                <li><a href="<?=base_url('Statistics/index/'.$character_id.'/90?aggr='.$aggregate)?>">Last 3 months</a></li>
                                <li><a href="<?=base_url('Statistics/index/'.$character_id.'/180?aggr='.$aggregate)?>">Last 6 months</a></li>
                                <li><a href="<?=base_url('Statistics/index/'.$character_id.'/365?aggr='.$aggregate)?>">Last 12 months</a></li>
                            </ul>
                        </div>    
                        Statistics from last <?=$interval?> days
                    </div>
                    <div class="panel-body">
                        <i class="fa fa-info yellow"></i> Here you can check more in-depht data about your revenue. <br>
                        <i class="fa fa-info yellow"></i> Results from this page in individual view assume the currently selected character as the seller (with any others as buyer) 
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> Statistics</a></li>
                    <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false"> Trade volumes</a></li>
                    <li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="false"> Profit distribution</a></li>
                    <li class=""><a data-toggle="tab" href="#tab-4" aria-expanded="false"> Daily snapshot</a></li>
                </ul>
            </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tab-content">
                    <div id="tab-3" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-filled">
                                    <div class="panel-heading">
                                        <div class="panel panel-filled panel-c-success panel-collapse">
                                            <div class="panel-heading">
                                                <h5><i class="fa fa-area-chart"></i> Profit distribution per item</h4>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                        <?php  $pieChart = new FusionCharts("pie2d", "mypiechart", "100%", "500", "pie", "json", $raw_chart);
                                               $pieChart->render(); ?>
                                            <div id="pie">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-2" class="tab-pane">
                        <div class="panel panel-filled">             
                            <div class="panel-heading">
                                <div class="panel panel-filled panel-c-success panel-collapse">
                                    <div class="panel-heading">
                                        <h5><i class="fa fa-bar-chart-o fa-fw"></i> Trade Volumes</h4>
                                </div>
                                </div>   
                            </div>   
                            <div class="panel-body statistics-body">
                                <?php $newchart = new FusionCharts("mscolumn2d", "sales", "100%", 500, "chart", "json", $chart);
                                $newchart->render();?>
                                <div id="chart">
                                </div>            
                            </div>
                        </div>
                    </div>

                    <div id="tab-1" class="tab-pane active">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="panel panel-filled">
                                    <div class="panel-heading">
                                        <div class="panel panel-filled panel-c-success panel-collapse">
                                            <div class="panel-heading">
                                                <h5><i class="fa fa-thumbs-o-up"></i> Best Items (by raw profit)</h4>
                                                <small>Items that made you the highest profit with their combined sales</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-hover table-stripped" id="bestraw">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Quantity</th>
                                                        <th>Profit</th>
                                                    </tr>
                                                </thead>    
                                                <tbody>
                                                    <?php foreach($best_raw as $row) {?>
                                                    <tr>
                                                        <td><a href="<?=base_url('Profits/index/'.$character_id.'/'.$interval.'?aggr='.$aggregate.'#'.$row['item'])?>">
                                                            <img src="<?=$row['url']?>" alt="icon"><?=$row['item']?></a>
                                                        </td>
                                                        <td><?=number_format($row['quantity'],0)?></td>
                                                        <td><?=number_format($row['profit'],2)?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>  
                                    </div>
                                </div>

                                <div class="panel panel-filled">
                                    <div class="panel-heading">
                                        <div class="panel panel-filled panel-c-success panel-collapse">
                                            <div class="panel-heading">
                                                <h5><i class="fa fa-dollar"></i> Best ISK/h</h4>
                                                <small>Items with the best profit for the time they took to resell</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-hover table-stripped" id="bestiph">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Quantity</th>
                                                        <th>Profit</th>
                                                        <th>ISK/h</th>
                                                    </tr>
                                                </thead>    
                                                <tbody>
                                                    <?php foreach($best_iph as $row) {?>
                                                    <tr>
                                                        <td><a href="<?=base_url('Profits/index/'.$character_id.'/'.$interval.'?aggr='.$aggregate.'#'.$row['item'])?>">
                                                            <img src="<?=$row['url']?>" alt="icon"><?=$row['item']?></a>
                                                        </td>
                                                        <td><?=number_format($row['quantity'],0)?></td>
                                                        <td><?=number_format($row['profit'],2)?></td>
                                                        <td><?=number_format($row['iph'],2)?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>  
                                    </div>
                                </div>

                                <div class="panel panel-filled">
                                    <div class="panel-heading">
                                        <div class="panel panel-filled panel-c-success panel-collapse">
                                            <div class="panel-heading">
                                                <h5><i class="fa fa-smile-o"></i> Best customers </h4>
                                                <small>Players that made you the most profit with by purchasing your items</small> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-hover table-stripped" id="bestcus">
                                                <thead>
                                                    <tr>
                                                        <th>Client</th>
                                                        <th>Profit</th>
                                                    </tr>
                                                </thead>    
                                                <tbody>
                                                    <?php foreach ($best_customer as $row) {?>
                                                    <tr> 
                                                        <td><img src="<?=$row['url']?>" alt="icon"><?=$row['soldTo']?></td>
                                                        <td><?=number_format($row['profit'],2)?></td>
                                                    </tr>
                                                  <?php }?>
                                                </tbody>
                                            </table>
                                        </div>  
                                    </div>
                                </div>
                               

                                <div class="panel panel-filled">
                                    <div class="panel-heading">
                                        <div class="panel panel-filled panel-c-success panel-collapse">
                                            <div class="panel-heading">
                                                <h5><i class="fa fa-rotate-left"></i> Top Stations</h4>
                                                <small>Stations where you made the most profit</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-hover table-stripped" id="topstations">
                                                <thead>
                                                    <tr>
                                                        <th>Station</th>
                                                        <th>Profit</th>
                                                    </tr>
                                                </thead>    
                                                <tbody>
                                                    <?php foreach($best_stations as $row) {?>
                                                    <tr>
                                                        <td><?=$row['station']?></td>
                                                        <td><?=number_format($row['profit'],2)?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>  
                                    </div>
                                </div>

                                <div class="panel panel-filled">
                                    <div class="panel-heading">
                                        <div class="panel panel-filled panel-c-success panel-collapse">
                                            <div class="panel-heading">
                                                <h5><i class="fa fa-flag-checkered"></i> Possible blunders</h4>
                                                <small>Items with abnormally high or low profit margin (possible typos on pricing)</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-hover table-stripped" id="blunders">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Profit</th>
                                                        <th>Margin</th>
                                                    </tr>
                                                </thead>    
                                                <tbody>
                                                    <?php foreach($best_blunders as $row) {?>
                                                    <tr>
                                                        <td><a href="<?=base_url('Profits/index/'.$character_id.'/'.$interval.'?aggr='.$aggregate.'#'.$row['item'])?>">
                                                            <img src="<?=$row['url']?>" alt="icon"><?=$row['item']?></a>
                                                        </td>
                                                        <td><?=number_format($row['profit'],2)?></td>
                                                        <td><?=number_format($row['margin'],2)?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="panel panel-filled">
                                    <div class="panel-heading">
                                        <div class="panel panel-filled panel-c-success panel-collapse">
                                            <div class="panel-heading">
                                                <h5><i class="fa fa-money"></i> Best Items (by margin)</h4>
                                                <small> Items with the highest average profit margin with their combined sales</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-hover table-stripped" id="bestmargin">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Quantity</th>
                                                        <th>Margin</th>
                                                    </tr>
                                                </thead>    
                                                <tbody>
                                                    <?php foreach($best_margin as $row) {?>
                                                    <tr>
                                                        <td><a href="<?=base_url('Profits/index/'.$character_id.'/'.$interval.'?aggr='.$aggregate.'#'.$row['item'])?>">
                                                            <img src="<?=$row['url']?>" alt="icon"><?=$row['item']?></a>
                                                        </td>
                                                        <td><?=number_format($row['quantity'],0)?></td>
                                                        <td><?=number_format($row['margin'],2)?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>  
                                    </div>
                                </div>
                        
                                <div class="panel panel-filled">
                                    <div class="panel-heading">
                                        <div class="panel panel-filled panel-c-success panel-collapse">
                                            <div class="panel-heading">
                                                <h5><i class="fa fa-clock-o"></i> Fastest turnovers</h4>
                                                <small> Individual transactions that resold the fastest</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-hover table-stripped" id="fastest">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Time</th>
                                                        <th>Profit</th>
                                                    </tr>
                                                </thead>    
                                                <tbody>
                                                    <?php foreach($best_to as $row) {?>
                                                    <tr>
                                                        <td><img src="<?=$row['url']?>" alt="icon"><?=$row['item']?></td>
                                                        <td><?=$row['difference']?></td>
                                                        <td><?=number_format($row['total'],2)?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>  
                                    </div>
                                </div>
                         
                                <div class="panel panel-filled">
                                    <div class="panel-heading">
                                        <div class="panel panel-filled panel-c-success panel-collapse">
                                            <div class="panel-heading">
                                                <h5><i class="fa fa-rotate-left"></i> Best timezones </h4>
                                                <small>Profit distribution according to timezone</small> <br>
                                                <b>US:</b> 00PM ~ 08AM, <b>AU:</b> 08AM ~ 4PM, <b>EU:</b> 4PM ~ 00PM (UTC time)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-hover table-stripped" id="timezones">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 25%;">Timezone</th>
                                                        <th>Profit</th>
                                                    </tr>
                                                </thead>    
                                                <tbody>
                                                    <?php foreach($best_tz as $key => $value) {
                                                        if($key == "eu") {
                                                            $url = base_url('assets/img/eu.png');
                                                        } else if($key == "us") {
                                                            $url = base_url('assets/img/us.png');
                                                        } else {
                                                            $key == "au" ? $url = base_url('assets/img/au.png') : '';
                                                        }

                                                        ?>
                                                    <tr>
                                                        <td><img src="<?=$url?>" alt="icon"></td>
                                                        <td><?=number_format($value,2)?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>  
                                    </div>
                                </div>

                                <div class="panel panel-filled">
                                    <div class="panel-heading">
                                        <div class="panel panel-filled panel-c-success panel-collapse">
                                            <div class="panel-heading">
                                                <h5><i class="fa fa-frown-o"></i> Problematic Items</h4>
                                                <small>Items that resulted in a net loss</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-hover table-stripped" id="problematic">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Quantity</th>
                                                        <th>Profit</th>
                                                    </tr>
                                                </thead>    
                                                <tbody>
                                                    <?php foreach($problematic as $row) {?>
                                                    <tr>
                                                        <td><a href="<?=base_url('Profits/index/'.$character_id.'/'.$interval.'?aggr='.$aggregate.'#'.$row['item'])?>">
                                                            <img src="<?=$row['url']?>" alt="icon"><?=$row['item']?></a>
                                                        </td>
                                                        <td><?=number_format($row['quantity'],0)?></td>
                                                        <td><?=number_format($row['profit'],2)?></td>
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

                    <div id="tab-4" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="panel panel-filled panel-c-success panel-collapse">
                                    <div class="panel-heading">
                                        <h5><i class="fa fa-flag-checkered"></i> Last <?=$interval?> days recap</h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-responsive table-bordered table-stripped" id="daily">
                                                <thead>
                                                    <tr>
                                                        <th>Day</th>
                                                        <th>Total Buy</th>
                                                        <th>Total Sell</th>
                                                        <th>Total Profit</th>
                                                        <th>Margin</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($profits_table['daily'] as $row) {
                                                    $row['margin'] > 0 ? $class="success" : 
                                                        ($row['margin'] < 0 ? $class="danger" : $class="");
                                                    ?>
                                                    <tr class= "<?=$class?>">
                                                        <td><?=$row['date']?></td>
                                                        <td><?=number_format($row['total_buy'],2)?></td>
                                                        <td><?=number_format($row['total_sell'],2)?></td>
                                                        <td><?=number_format($row['total_profit'],2)?></td>
                                                        <td><?=number_format($row['margin'],2)?></td>
                                                    </tr>
                                                <?php } ?>
                                                <?php foreach($profits_table['total'] as $row) {?>
                                                    <tr class="yellow">
                                                        <td>GRAND TOTAL</td>
                                                        <td><b><?=number_format($row['total_buy'],2)?></b></td>
                                                        <td><b><?=number_format($row['total_sell'],2)?></b></td>
                                                        <td><b><?=number_format($row['total_profit'],2)?></b></td>
                                                        <td><b><?=number_format($row['margin'],2)?></b></td>
                                                    </tr>    
                                                <?php }?>
                                                </tbody>
                                            </table>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</section>
