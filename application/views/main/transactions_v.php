<script src="<?=base_url('assets/js/transactions-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
                                <a href="<?=base_url('Transactions/index/' . $character_list['chars'][$i]) . '/' . $interval . '?aggr=0'?>">
                                    <?=$character_list['char_names'][$i]?>
                                </a>
                            </li>
                            <?php
}
?>
                            <li class="divider" role="separator">
                            </li>
                            <?php $url = "Transactions/index/" . $character_id . '/' . $interval . "?aggr=1" ;?>
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
                            <i class="pe page-header-icon pe-7s-menu">
                            </i>
                            <?php }?>
                    </div>
                    <div class="header-title">
                        <h1>
                            <?php echo $aggregate == 1 ? implode(' + ', $char_names) : $character_name ?>
                            's Transactions
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
                        <div class="panel-tools">
                            <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                            <a class="panel-close"><i class="fa fa-times"></i></a>
                        </div>
                        Transactions from last <?=$interval?> days
                    </div>
                    <div class="panel-body">
                        <ul class="info-panel-main">
                            <li><i class="fa fa-info yellow"></i> You can unlink certain transactions from being evaluated in the Profit calculator (e.g items for personal use or that you don't intend to re-sell)</li>
                            <li><i class="fa fa-info yellow"></i> Transactions already processed as profits are marked with a <span class="yellow">P</span> and cannot be unlinked. You can unlink unprocessed buy transactions so that they aren't taken into account later on when calculating profits</li>
                            <li><i class="fa fa-warning yellow"></i> Unlinking transactions is <span class="yellow">irreversible</span> and can negatively impact the profit calculations if done incorrectly. Please use caution!</li>
                        </ul>
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
                                <li><a href="<?=base_url('Transactions/index/'.$character_id.'/1?aggr='.$aggregate)?>">Last 24 hours</a></li>
                                <li><a href="<?=base_url('Transactions/index/'.$character_id.'/7?aggr='.$aggregate)?>">Last 7 days</a></li>
                                <li><a href="<?=base_url('Transactions/index/'.$character_id.'/14?aggr='.$aggregate)?>">Last 14 days</a></li>
                                <li><a href="<?=base_url('Transactions/index/'.$character_id.'/30?aggr='.$aggregate)?>">Last 30 days</a></li>
                                <li><a href="<?=base_url('Transactions/index/'.$character_id.'/60?aggr='.$aggregate)?>">Last 2 months</a></li>
                                <li><a href="<?=base_url('Transactions/index/'.$character_id.'/90?aggr='.$aggregate)?>">Last 3 months</a></li>
                                <li><a href="<?=base_url('Transactions/index/'.$character_id.'/365?aggr='.$aggregate)?>">Last 12 months</a></li>
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
                                    <?php 
                                    foreach($transactions as $row) {
                                            $row->type == 'Buy' ? $button = 'btn-danger' : $button = 'btn-success';
                                            !empty($row->proc) ? $unlink = "P" : ($row->type == 'Buy' ? $unlink = 1 : $unlink = "-");
                                            if($unlink == 1) {
                                                $unlink = "<button type='button' class='btn btn-default btn-unlink' data-toggle='modal' data-target='#unlink' data-transaction='$row->transaction_id'> 
                                                Unlink</button>";
                                            }
                                        ?>
                                    <tr>
                                        <td><?=$row->time?></td>
                                        <td><img src="https://image.eveonline.com/Type/<?=$row->item_id?>_32.png" alt="item icon"><?=$row->item_name?></td>
                                        <td><?=number_format($row->quantity,0)?></td>
                                        <td><?=number_format($row->price_unit,2)?></td>
                                        <td><?=number_format($row->price_total,2)?></td>
                                        <td><span class="btn btn-xs <?=$button?>"><?=$row->type?></span></td>
                                        <td><?=$row->client?></td>
                                        <td><?=$row->station_name?></td>
                                        <td><?=$row->character_name?></td>
                                        <td><?=$unlink?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <div class="modal fade" id="unlink" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" data-url="<?=base_url('Transactions/unlink')?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                            <h4 class="modal-title">Unlink Transaction</h4>
                                        </div>
                                        <div class="modal-body">
                                            <h4 class="m-t-none"><i class="fa fa-warning"></i> Please make sure you know what you're doing!</h4>
                                            <p>This operation will remove the item from the profit queue. This means future sales of this item will not take this transaction into account. Only do this if you don't intend to re-sell this item in future for profit. This can <span class="yellow">negatively</span> impact your profits calculations in future if done incorrectly.</p>
                                            <p>Unlinking this transaction is <span class="yellow">irreversible</span>. Are you sure you want to continue?</p>
                                            
                                            <div class="text-center">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-accent btn-unlink-confirm" data-url="0">Save changes</button>
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
    </div>
</section>
