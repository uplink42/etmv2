<?php
include_once 'assets/fusioncharts/php-wrapper/fusioncharts.php';
?>
<!--Fusioncharts -->
<script src="<?=base_url('assets/fusioncharts/js/fusioncharts.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<script src="<?=base_url('assets/js/profits-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="dropdown pull-right">
                        <button aria-expanded="true" aria-haspopup="true" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="dropdownmenu-characters" type="button">
                            Character
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <?php
for ($i = 0; $i < count($character_list['chars']); $i++) {
    ?>
                            <li>
                                <a href="<?=base_url('Profits/index/' . $character_list['chars'][$i]) . '/' . $interval . '?aggr=0'?>">
                                    <?=$character_list['char_names'][$i]?>
                                </a>
                            </li>
                            <?php
}
?>
                            <li class="divider" role="separator">
                            </li>
                            <?php $url = "Profits/index/" . $character_id . '/' . $interval . "?aggr=1" ;?>
                            <li>
                                <a href="<?=base_url($url)?>">
                                    <b>
                                        All
                                    </b>
                                </a>
                            </li>
                        </ul>
                    </div>
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
                            's Profit Breakdown
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
                        Profits from last <?=$interval?> days
                    </div>
                    <div class="panel-body">
                        <i class="fa fa-info yellow"></i> This page detects all item <span class="yellow">resales</span> and calculates profits made. Broker fees are always assumed regardless if you bought an item from a buy order or a sell order. <br />
                        <i class="fa fa-info yellow"></i>  Broker fees and transaction taxes are already included in prices. <br />
                        <i class="fa fa-info yellow"></i> Profits are calculated using a first-in, first-out <span class="yellow"><a href="https://en.wikipedia.org/wiki/FIFO_and_LIFO_accounting" target="_blank">(FIFO)</a></span> inventory management method.
                    </div>
                </div> 
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <?php 
                        $columnChart = new FusionCharts("line", "profitchart" , "100%", 400, "chart-2", "json", $chart);
                        $columnChart->render();?>
                            <div id="chart-2">
                            </div>
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
                                <li><a href="<?=base_url('Profits/index/'.$character_id.'/1?aggr='.$aggregate)?>">Last 24 hours</a></li>
                                <li><a href="<?=base_url('Profits/index/'.$character_id.'/7?aggr='.$aggregate)?>">Last 7 days</a></li>
                                <li><a href="<?=base_url('Profits/index/'.$character_id.'/14?aggr='.$aggregate)?>">Last 14 days</a></li>
                                <li><a href="<?=base_url('Profits/index/'.$character_id.'/30?aggr='.$aggregate)?>">Last 30 days</a></li>
                                <li><a href="<?=base_url('Profits/index/'.$character_id.'/60?aggr='.$aggregate)?>">Last 2 months</a></li>
                                <li><a href="<?=base_url('Profits/index/'.$character_id.'/90?aggr='.$aggregate)?>">Last 3 months</a></li>
                                <li><a href="<?=base_url('Profits/index/'.$character_id.'/180?aggr='.$aggregate)?>">Last 6 months</a></li>
                                <li><a href="<?=base_url('Profits/index/'.$character_id.'/365?aggr='.$aggregate)?>">Last 12 months</a></li>
                            </ul>
                        </div>
                        <div class="panel-tools">
                        </div>
                    </div>
                    <br>
                    <div class="panel-body profits-2-body">
                        <p class="yellow"></p>
                        <p class="yellow-2"> Click an item name to filter results.</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="profits-2-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th style="width: 5%;"></th>
                                        <th>System</th>
                                        <th>ISK/unit</th>
                                        <th>Q</th>
                                        <th>ISK/total</th>
                                        <th>Time</th>
                                        <th>Character</th>
                                        <th>ISK Profit</th>
                                        <th>Margin</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                foreach($profits as $row) {
                                    $row['profit_total'] >0 ? $res = "success" : $res="danger";
                                    $row['character_buy'] == $row['character_sell'] ? $row['character_sell'] = "" : "";
                                    $row['sys_buy'] == $row['sys_sell'] ? $row['sys_sell'] = "" : "";
                                    $img ? $url = "https://image.eveonline.com/Type/".$row['item_id']."_32.png" : $url="";
                                    ?>
                                    <tr class="<?=$res?>">
                                        <td><?php echo $img ? "<img src=".$url." alt=''>" : ''?>
                                            <a class="item-name" style="color: #fff"><?=$row['item_name']?></a></td>
                                        <td><span class="btn btn-xs btn-danger">B</span><br/><span class="btn btn-xs btn-success">S</span></td>
                                        <td><?=$row['sys_buy']?><br/>
                                            <?=$row['sys_sell']?></td>
                                        <td><?=number_format($row['buy_price'],2)?><br />
                                            <?=number_format($row['sell_price'],2)?></td>
                                        <td><?=number_format($row['profit_quantity'],0)?></td>
                                        <td><?=number_format($row['buy_price_total'],2)?><br />
                                            <?=number_format($row['sell_price_total'],2)?></td>
                                        <td><?=$row['time_buy']?> <br />
                                            <?=$row['time_sell']?></td>
                                        <td><?=$row['character_buy']?> <br />
                                            <?=$row['character_sell']?></td>    
                                        <td><?=number_format($row['profit_total'],2)?></td>
                                        <td><a class= "btn btn-default btn-xs"><?=number_format($row['margin'],2)?></a></td>
                                        <td><?=$row['diff']?></td>
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
</section>
