<?php
include_once 'assets/fusioncharts/php-wrapper/fusioncharts.php';
?>
<!--Fusioncharts -->
<script src="<?=base_url('assets/fusioncharts/js/fusioncharts.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<script src="<?=base_url('assets/js/nwtracker-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
                            <i class="pe page-header-icon pe-7s-graph3">
                            </i>
                            <?php }?>
                    </div>
                    <div class="header-title">
                        <h1>
                            <?php echo $aggregate == 1 ? implode(' + ', $char_names) : $character_name?>
                            's Net worth Tracker
                        </h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled panel-c-success">
                    <div class="panel-body">
                        <i class="fa fa-info yellow"></i> Data is pulled once a day at 00.00 AM GMT time, and updates for every login past that point in each day.<br/>
                        <i class="fa fa-info yellow"></i> Item values are based on Eve's estimated prices (updated several times a day)
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <div class="dropdown pull-right">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-interval" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-bottom: 20px;">
                            Time Interval
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-interval">
                              <li ><a href="<?=base_url('NetworthTracker/index/'.$character_id.'/1?aggr='.$aggregate)?>">Last 7 days</a></li>
                              <li><a href="<?=base_url('NetworthTracker/index/'.$character_id.'/30?aggr='.$aggregate)?>">Last 30 days</a></li>
                              <li><a href="<?=base_url('NetworthTracker/index/'.$character_id.'/60?aggr='.$aggregate)?>">Last 2 months</a></li>
                              <li><a href="<?=base_url('NetworthTracker/index/'.$character_id.'/180?aggr='.$aggregate)?>">Last 6 months</a></li>
                              <li><a href="<?=base_url('NetworthTracker/index/'.$character_id.'/365?aggr='.$aggregate)?>">Last 12 months</a></li>
                            </ul>
                        </div>
                        <?php
                            $multichart = new FusionCharts("msline", "sales", "100%", "500", "nw", "json", $chart);
                            $multichart->render();
                        ?>
                        <div id="nw"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
