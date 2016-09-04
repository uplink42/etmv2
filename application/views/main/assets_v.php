<script src="<?=base_url('assets/js/assets-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
for ($i = 0; $i < count($character_list); $i++) {
    ?>
                            <li>
                                <a href="<?=base_url('Assets/index/' . $character_list['chars'][$i]) . '/'. $region_id . '?sig='.$sig.'&aggr=0'?>">
                                    <?=$character_list['char_names'][$i]?>
                                </a>
                            </li>
                            <?php
}
?>
                            <li class="divider" role="separator">
                            </li>
                            <?php $url = "Assets/index/" . $character_id . "/" . $region_id . "?sig='.$sig.'&aggr=1" ;?>
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
                            <i class="pe page-header-icon pe-7s-plugin">
                            </i>
                            <?php }?>
                    </div>
                    <div class="header-title">
                        <h1>
                            <?php echo $aggregate == 1 ? implode(' + ', $char_names) : $character_name ?>
                            's Assets
                        </h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled panel-c-success">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="panel-toggle">
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover table-responsive">
                            <tr>
                                <th>Est Assets Value
                                <strong class="m-t-xs yellow-2"><?=number_format($current_asset_value)?> ISK</strong></th>
                            </tr>
                            </table> 
                        <div class="text-center">
                            <?php if($sig==1) {?>
                            <a href="<?=base_url('Assets/index/'.$character_id. '/'.$region_id. '?aggr='.$aggregate.'&sig=0')?>"><p class="btn btn-w-md btn-warning warning-asset">Currently only displaying the most significant items which represent 
                                <span class="btn btn-default btn-xs"><?=number_format($ratio,2)?>%</span> 
                            of your asset value. Click here to see the full item list (page may be slower to load and export features may crash your browser if you have too many items).</p></a>
                            <?php } else {?>
                                <a href="<?=base_url('Assets/index/'.$character_id. '/'.$region_id. '?aggr='.$aggregate.'&sig=1')?>"><p class="btn btn-w-md btn-warning warning-asset">Currently displaying all items. For faster page loads and exports you may request only the most significant items by clicking here, which would represent <span class="btn btn-default btn-xs"><?=number_format($ratio,2)?>%</span> of your  asset value.</p></a>
                            <?php } ?>
                        </div>       
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="panel panel-filled panel-c-success">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="panel-toggle">
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover table-responsive">
                            <tr>
                                <th>Selected Region: <span class="yellow-2"><?=$region_name?></span></th>
                            </tr>
                            <tr>
                                <th>Region: 
                                    <div class="dropdown pull-right">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-interval" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Region selection
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right dropdown-interval">
                                                <?php $url = "Assets/index/".$character_id."/all?sig=".$sig."&aggr=".$aggregate;?>
                                                <li><a href="<?=base_url($url)?>"><b>All</b></a></li>
                                                <li role="separator" class="divider"></li>
                                        <?php 
                                        foreach($totals as $key => $row) {
                                            ?>
                                            <li  data-id=""><a href="<?=base_url('Assets/index/'.$character_id.'/'.$row[0]['region_id']."?sig=".$sig."&aggr=".$aggregate)?>">
                                            <?php echo $key . " (" . number_format($row[0]['total_value']/1000000000,3) . " b)"?></a></li>
                                            <?php
                                        }
                                        ?>
                                        </ul>
                                    </div>
                                </th>
                            </tr>
                        </table>
                    </div>    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel-body">
                <div class="m-t-sm">
                    <div class="c-white"><span class="label label-accent"></span> Recent Assets evolution (all regions for current characters)
                    </div>
                        <!--<span class="sparkline"></span>-->
                </div>
            </div>
            <div class="col-lg-12 col-xs-12">
                <div class="panel panel-filled" style="position:relative;height: 114px">
                    <div style="position: absolute;bottom: 0;left: 0;right: 0">
                        <span class="sparkline" data-profit="<?= $graph_data?>"></span>
                    </div>
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="panel-tools">
                        </div>
                    </div>
                    <div class="panel-body assets-body">
                        <p>This list contains all assets inside stations, POSes, hangars and containers. Citadel assets are currently not supported by the API.
                        </p>
                        <p class="yellow"></p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="assets-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Owner</th>
                                        <th>Quantity</th>
                                        <th>Location</th>
                                        <th>Value (unit)</th>
                                        <th>Value (stack)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($asset_list as $asset) {
                                        $id = $asset['item_id'];

                                        ?>
                                        <tr>
                                            <td><?=$asset['item_name']?></td>
                                            <td><?=$asset['owner']?></td>
                                            <td><?=number_format($asset['quantity'],0)?></td>
                                            <td><?=$asset['loc_name']?></td>
                                            <td><?=number_format($asset['unit_value'],2)?></td>
                                            <td><?=number_format($asset['total_value'],2)?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
