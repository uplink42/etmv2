<?php
include_once 'assets/fusioncharts/php-wrapper/fusioncharts.php';
?>
<!--Fusioncharts -->
<script src="<?=base_url('assets/fusioncharts/js/fusioncharts.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<script src="<?=base_url('assets/luna/vendor/datatables/datatables.min.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
              <li><a href="#">Last 3 days</a></li>
              <li><a href="#">Last 7 days</a></li>
              <li><a href="#">Last 14 days</a></li>
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
                <?=$new_info->contracts?>
                <span class="slight">
                    <?php if ($new_info->contracts > 0) {
                        echo "<i class='fa fa-play fa-rotate-270 text-warning'></i>";
                    }
                    ?>
                </span>
                </h2>
                <div class="small">New Contracts</div>
            </div>
            </div>
        </div>
        <div class="col-lg-2 col-xs-6">
            <div class="panel panel-filled">
            <div class="panel-body">
                <h2 class="m-b-none">
                <?=$new_info->transactions?>
                <span class="slight">
                    <?php if ($new_info->transactions > 0) {
                        echo "<i class='fa fa-play fa-rotate-270 text-warning'></i>";
                    }
                    ?>
                </span>
                </h2>
                <div class="small">New transactions</div>
            </div>
            </div>
        </div>
        <div class="col-lg-2 col-xs-6">
            <div class="panel panel-filled">
            <div class="panel-body">
                <h2 class="m-b-none">
                <?=$new_info->orders?>
                <span class="slight">
                    <?php if ($new_info->orders > 0) {
                        echo "<i class='fa fa-play fa-rotate-270 text-warning'></i>";
                    }
                    ?>
                </span>
                </h2>
                <div class="small">New orders</div>

            </div>
            </div>
        </div>

        <div class="col-lg-4 col-lg-offset-2 col-xs-12">
            <div class="panel panel-filled" style="position:relative;height: 114px">
            <div style="position: absolute;bottom: 0;left: 0;right: 0">
                <span class="sparkline"></span>
            </div>
            <div class="panel-body">
                <div class="m-t-sm">
                    <div class="c-white"><span class="label label-accent"></span> Last 7 day profits (all characters)</div>
                        <span class="small c-white"><?=number_format($profits_trends['total_week'], 2) . " ISK";?>
                            <i class="fa fa-play fa-rotate-270 text-warning"></i>
                            <?=$profits_trends['trend_today']?>% (today)
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
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Time Interval
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
              <li><a href="#">Last 3 days</a></li>
              <li><a href="#">Last 7 days</a></li>
              <li><a href="#">Last 14 days</a></li>
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
                                    <th>Item</th>
                                    <th>Station</th>
                                    <th>Quantity</th>
                                    <th>Profit</th>
                                    <th>Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                foreach ($profits as $row) {
                                    ?>
                                <tr>
                                    <td><?=$row->item_name?></td>
                                    <td><?=$row->station_name?></td>
                                    <td><?=$row->quantity?></td>
                                    <td><?=$row->profit_unit?></td>
                                    <td><?=$row->margin?></td>
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
                        $pieChart = new FusionCharts("pie3d", "mypiechart", "100%", "300", "pie", "json", $pie_data);
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
