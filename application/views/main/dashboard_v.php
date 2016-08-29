<?php
include_once 'assets/fusioncharts/php-wrapper/fusioncharts.php';
?>
<!--Fusioncharts -->
<script src="<?=base_url('assets/fusioncharts/js/fusioncharts.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<script src="<?=base_url('assets/js/dashboard-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
        <div class="col-lg-12">
            <div class="view-header">
            <div class="dropdown pull-right">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownmenu-characters" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Character
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="characters-dropdown">
            <?php 
                foreach($character_list as $row) {
                    ?>
                <li><a href="<?=base_url('Dashboard/index/'.$row->id.'/')?>"><?=$row->name?></a></li>    
            <?php    }
            ?>
                <li role="separator" class="divider"></li>
                <li><a href="<?=base_url('Dashboard/index/all/')?>"><b>All</b></a></li> 
            </ul>
            </div>
            <div class="header-icon">
                <i class="pe page-header-icon pe-7s-shield"></i>
            </div>
            <div class="header-title">
                <h1>Dashboard</h1>
            </div>

            </div>
            <hr>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-2 col-xs-6">
            <div class="panel panel-filled">
                <div class="panel-body">
                    <h2 class="m-b-none">
                        <i class="pe-7s-id"></i>
                        <?=$new_info->contracts?>
                    <span class="slight">
                        <?php if ($new_info->contracts > 0) {
                            echo "<i class='fa fa-play fa-rotate-270 text-warning'></i>";
                        }
                        ?>
                    </span>
                    </h2>
                    <div>New Contracts</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-xs-6">
            <div class="panel panel-filled">
                <div class="panel-body">
                    <h2 class="m-b-none">
                        <i class="pe-7s-menu"></i>
                        <?=$new_info->transactions?>
                    <span class="slight">
                        <?php if ($new_info->transactions > 0) {
                            echo "<i class='fa fa-play fa-rotate-270 text-warning'></i>";
                        }
                        ?>
                    </span>
                    </h2>
                    <div>New transactions</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-xs-6">
            <div class="panel panel-filled">
                <div class="panel-body">
                    <h2 class="m-b-none">
                    <i class="pe-7s-cart"></i>
                        <?=$new_info->orders?>
                    <span class="slight">
                        <?php if ($new_info->orders > 0) {
                            echo "<i class='fa fa-play fa-rotate-270 text-warning'></i>";
                        }
                        ?>
                    </span>
                    </h2>
                    <div>New orders</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-lg-offset-2 col-xs-12">
            <div class="panel panel-filled" style="position:relative;height: 114px">
            <div style="position: absolute;bottom: 0;left: 0;right: 0">
                <span class="sparkline" data-profit=<?= $week_profits?>></span>
            </div>
            <div class="panel-body">
                <div class="m-t-sm">
                    <div class="c-white"><span class="label label-accent"></span> Last 7 day profits (all characters)</div>
                        <span class="small c-white">This week: <?=number_format($profits_trends['total_week'], 0) . " ISK";?>
                            <?php
                            $profits_trends['trend_today'] > 0 ? $res = "270" : $res="90"?>
                            <i class="fa fa-play fa-rotate-<?=$res?> text-warning"></i>
                            <?=number_format($profits_trends['trend_today'],0)?>% (today)
                        </span>
                <!--<span class="sparkline"></span>-->
                </div>
            </div>
            </div>
        </div>

        </div>
        <div class="row">
        <div class="col-md-8">
            <div class="dropdown pull-right">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-interval" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Time Interval
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right dropdown-interval" aria-labelledby="dropdownMenu1">
                  <li  data-id="1"><a href="<?=base_url('Dashboard/index/'.$character_id.'/1')?>">Last 24 hours</a></li>
                  <li  data-id="3"><a href="<?=base_url('Dashboard/index/'.$character_id.'/3')?>">Last 3 days</a></li>
                  <li  data-id="60"><a href="<?=base_url('Dashboard/index/'.$character_id.'/7')?>">Last 7 days</a></li>
                </ul>
            </div>
            <div class="panel">
                <div class="panel-heading">
                    Latest profits
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="profits-table" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Item</th>
                                    <th>System</th>
                                    <th>Date</th>
                                    <th>Q</th>
                                    <th>Profit</th>
                                    <th>Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                  foreach ($profits as $row) {
                                     if($row['profit_unit'] >0 ? $res = "success" : $res="danger");
                                      ?>
                                 <tr class="<?=$res?>">
                                     <td><img src="<?=$row['url']?>" alt="icon"></img><?=$row['item_name']?></td>
                                     <td><?=$row['system_name']?></td>
                                     <td><?=$row['sell_time']?></td>
                                     <td><?=number_format($row['quantity'],0)?></td>
                                     <td><?=number_format($row['profit_total'],2)?></td>
                                     <td><?=number_format($row['margin'],2)?></td>
                                  </tr>
                                      <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-heading">
                    <?php
                        $pieChart = new FusionCharts("pie2d", "mypiechart", "100%", "300", "pie", "json", $pie_data);
                        $pieChart->render();
                    ?>
                    Asset distribution
                </div>
            </div>
            <div id="pie">
            </div>
        </div>
    </div>
    </div>
</section>
