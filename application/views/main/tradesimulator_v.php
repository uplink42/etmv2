<script src="<?=base_url('assets/js/tradesimulator-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <?php $this->load->view('common/selector_v');?>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-magic-wand">
                        </i>
                    </div>
                    <div class="header-title">
                        <h1>
                            Trade Simulator
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
                        <i class="fa fa-info yellow"></i> You can quickly check price differences between systems and regions here. <br/>
                        <i class="fa fa-info yellow"></i> You must first create Stock Lists for the items you wish to submit in this page <br />
                        <i class="fa fa-info yellow"></i> Broker fees and transaction taxes are automatically calculated based on your standings and skills for the chosen characters <br />
                        <i class="fa fa-info yellow"></i> Citadel market data is not available yet
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                    </div>
                    <div class="panel-body">
                        <div class="tradesim" data-res="<?php echo isset($results) ? 'true' : 'false' ?>">
                            <form name="tradesimulator" method="POST" action="<?=base_url('TradeSimulator/process/' . $character_id . '?aggr=' . $aggregate)?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-center hourglass">
                                            <i class="fa fa-hourglass-start"></i> BUY
                                        </div>
                                        <div class="form-group">
                                            <label for="origin-station">Origin station: </label>
                                            <input type="text" class="form-control" name="origin-station" id="origin-station" placeholder="Type in a station name (or select a Trade Route below)">
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="buy-method" value="buy" checked>
                                                I will purchase my items from buy orders
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="buy-method" value="sell">
                                                I will purchase my items from sell orders
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label for="buyer">Buyer character: </label>
                                            <select class="form-control" name="buyer" id="buyer">
                                            <?php for ($i = 0; $i < count($character_list['chars']); $i++) {?>
                                              <option value="<?=$character_list['chars'][$i]?>"><?=$character_list['char_names'][$i]?></option>
                                               <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center hourglass">
                                            <i class="fa fa-hourglass-end"></i> SELL
                                        </div>
                                        <div class="form-group">
                                            <label for="destination-station">Destination station: </label>
                                            <input type="text" class="form-control" id="destination-station" name="destination-station" placeholder="Type in a station name (or select a Trade Route below)">
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="sell-method" value="buy">
                                                I will sell my items to buy orders
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="sell-method" value="sell" checked>
                                                I will sell my items to sell orders
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label for="seller">Seller character: </label>
                                            <select class="form-control" name="seller" id="seller">
                                             <?php for ($i = 0; $i < count($character_list['chars']); $i++) {?>
                                              <option value="<?=$character_list['chars'][$i]?>"><?=$character_list['char_names'][$i]?></option>
                                               <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="form-group">
                                            <label for="stocklist">Stock List: </label>

                                            <select class="form-control" name="stocklist" id="stocklist">
                                            <?php foreach ($stocklists as $row) {?>
                                                <option value="<?=$row->iditemlist?>"><?=$row->name?></option>
                                            <?php }?>
                                            </select>
                                        </div>
                                        <p><i class="fa fa-info"></i> You must create a Stock List first</p>

                                        <div class="form-group">
                                            <label for="traderoute">Trade Route (optional): </label>
                                            <select class="form-control" name="traderoute" id="traderoute">
                                                <option value="default">Select a Trade Route below</option>
                                            <?php foreach ($traderoutes as $row) {?>
                                              <option value="<?=$row->id?>"><?=$row->s1 . " >> " . $row->s2?></option>
                                              <?php }?>
                                            </select>
                                        </div>
                                        <p><i class="fa fa-info"></i> Selecting a Trade Route will auto-fill starting and destination stations</p>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <button class="btn btn-default btn-submit-ts">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="tradesim-res">
                            <div class="row">
                                <div class="text-center col-md-12">
                                    <b>Stock List:</b> <?=$results['req']['list']?>
                                </div>
                            </div>
                            <div class="row row-bordered">
                                <div class="col-md-6 col-xs-12 text-center"><p><b>Origin</b></p>
                                    <div class="row text-left">
                                        <div class="col-md-4 col-xs-6"><b>Character</b></div>
                                        <div class="col-md-8 col-xs-6"><?=$results['req']['buy_character']?></div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-md-4 col-xs-6"><b>Station</b></div>
                                        <div class="col-md-8 col-xs-6"><?=$results['req']['buy_station']?></div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-md-4 col-xs-6"><b>Order type</b></div>
                                        <div class="col-md-8 col-xs-6"><?=$results['req']['buy_method']?> order</div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-md-4 col-xs-6"><b>Broker Fee</b></div>
                                        <div class="col-md-8 col-xs-6"><?=number_format($results['req']['buy_broker'], 2) . ' %'?></div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-md-4 col-xs-6"><b>Transaction Tax</b></div>
                                        <div class="col-md-8 col-xs-6"><?=number_format($results['req']['buy_tax'], 2) . ' %'?></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12 text-center"><p><b>Destination</b></p>
                                    <div class="row  text-left">
                                        <div class="col-md-4 col-xs-6"><b>Character</b></div>
                                        <div class="col-md-8 col-xs-6"><?=$results['req']['sell_character']?></div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-md-4 col-xs-6"><b>Station</b></div>
                                        <div class="col-md-8 col-xs-6"><?=$results['req']['sell_station']?></div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-md-4 col-xs-6"><b>Order type</b></div>
                                        <div class="col-md-8 col-xs-6"><?=$results['req']['sell_method']?> order</div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-md-4 col-xs-6"><b>Broker Fee</b></div>
                                        <div class="col-md-8 col-xs-6"><?=number_format($results['req']['sell_broker'], 2) . ' %'?></div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-md-4 col-xs-6"><b>Transaction Tax</b></div>
                                        <div class="col-md-8 col-xs-6"><?=number_format($results['req']['sell_tax'], 2) . ' %'?></div>
                                    </div>
                                </div>
                            </div>
                            <p></p>

                            <div class="table-responsive">
                                <table id="ts-table" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Vol (m<sup>3</sup>)</th>
                                            <th class="yellow">Buy Price</th>
                                            <th>Broker Fee (B)</th>
                                            <th class="yellow">Sell Price</th>
                                            <th>Broker Fee (S)</th>
                                            <th>Transaction Tax (S)</th>
                                            <th class="yellow">Raw Profit</th>
                                            <th>Profit/m<sup>3</sup></th>
                                            <th>Margin(%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($results['results'] as $row) {
                                        $id   = $row['id'];
                                        $img  = "https://image.eveonline.com/Type/" . $id . "_32.png";
                                        $name = $row['name'];
                                        if ($row['profit_raw'] > 0 ? $type = "success" : $type = "danger");

                                        ?>
                                        <tr class="<?=$type?>">
                                            <td><img src="<?=$img?>" alt="icon"> <?=$name?></td>
                                            <td><?=number_format($row['vol'], 2)?></td>
                                            <td><?=number_format($row['buy_price'], 2)?></td>
                                            <td><?=number_format($row['buy_broker'], 2)?></td>
                                            <td><?=number_format($row['sell_price'], 2)?></td>
                                            <td><?=number_format($row['sell_broker'], 2)?></td>
                                            <td><?=number_format($row['sell_tax'], 2)?></td>
                                            <td><?=number_format($row['profit_raw'], 2)?></td>
                                            <td><?=number_format($row['profit_m3'], 2)?></td>
                                            <td><a class= "btn btn-default btn-xs"><?=number_format($row['profit_margin'], 2)?></a></td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php $this->load->view('common/loader_v')?>
                </div>
            </div>
        </div>
    </div>
</section>
