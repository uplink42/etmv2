<script src="<?=base_url('dist/js/apps/transactions-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
                <div class="panel panel-filled panel-c-success panel-main">
                    <div class="panel-heading">
                        Transactions from last <?=$interval?> days
                    </div>
                    <div class="panel-body">
                        <i class="fa fa-info yellow"></i> You can unlink certain transactions from being evaluated in the Profit calculator (e.g items for personal use or that you don't intend to re-sell) <br>
                        <i class="fa fa-info yellow"></i> Transactions already processed as profits are marked with a <span class="yellow">P</span> and cannot be unlinked. You can unlink unprocessed buy transactions so that they aren't taken into account later on when calculating profits <br>
                        <i class="fa fa-warning yellow"></i> Unlinking transactions is <span class="yellow">irreversible</span> and can negatively impact the profit calculations if done incorrectly. Please use caution!
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
                                <li><a href="<?=base_url('transactions/index/'.$character_id.'/1?aggr='.$aggregate)?>">Last 24 hours</a></li>
                                <li><a href="<?=base_url('transactions/index/'.$character_id.'/7?aggr='.$aggregate)?>">Last 7 days</a></li>
                                <li><a href="<?=base_url('transactions/index/'.$character_id.'/14?aggr='.$aggregate)?>">Last 14 days</a></li>
                                <li><a href="<?=base_url('transactions/index/'.$character_id.'/30?aggr='.$aggregate)?>">Last 30 days</a></li>
                                <li><a href="<?=base_url('transactions/index/'.$character_id.'/60?aggr='.$aggregate)?>">Last 2 months</a></li>
                                <li><a href="<?=base_url('transactions/index/'.$character_id.'/90?aggr='.$aggregate)?>">Last 3 months</a></li>
                                <li><a href="<?=base_url('transactions/index/'.$character_id.'/180?aggr='.$aggregate)?>">Last 6 months</a></li>
                                <li><a href="<?=base_url('transactions/index/'.$character_id.'/365?aggr='.$aggregate)?>">Last 12 months</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-default pull-right btn-clear">Clear filters</button>           
                    </div>   

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
                                            $unlink = $row->remaining == 0 || $row->type == 'Sell' ? '-' : 1;
                                            if($unlink == 1) {
                                                $unlink = "<button type='button' class='btn btn-default btn-unlink' data-toggle='modal' data-target='#unlink' data-transaction='$row->transaction_id'> 
                                                Unlink</button>";
                                            }
                                            $id = $row->item_id;
                                            $img ? $url = $row->url : $url="";
                                        ?>
                                    <tr>
                                        <td><?=$row->time?></td>
                                        <td><?php echo $img ? "<img src=".$url." alt=''>" : ''?>
                                            <a class="item-name" style="color: #fff"><?=$row->item_name?></a></td>
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
