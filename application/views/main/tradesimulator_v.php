<script src="<?=base_url('assets/js/tradesimulator-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
                                <a href="<?=base_url('TradeSimulator/index/' . $character_list['chars'][$i]) . '?aggr=0'?>">
                                    <?=$character_list['char_names'][$i]?>
                                </a>
                            </li>
                            <?php
}
?>
                            <li class="divider" role="separator">
                            </li>
                            <?php $url = "TradeSimulator/index/" . $character_id . "?aggr=1" ;?>
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
                        <i class="fa fa-info"></i> You can quickly check price differences between systems and regions here. <br/>
                        <i class="fa fa-info"></i> You must first create Stock Lists for the items you wish to submit in this page <br />
                        <i class="fa fa-info"></i> Broker fees and transaction taxes are automatically calculated based on your standings and skills for the chosen characters
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                    </div>
                    <div class="panel-body">
                        <form name="tradesimulator" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="origin-station">Origin station: </label>
                                        <input type="email" class="form-control" id="origin-station" placeholder="Type in a station name">
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="buy-method" id="buy-method" checked>
                                            I will purchase my items from buy orders
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="buy-method" id="buy-method">
                                            I will purchase my items from sell orders
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="buyer">Buyer character: </label>
                                        <select class="form-control" name="buyer">
                                          
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="destination-station">Destination station: </label>
                                        <input type="email" class="form-control" id="destination-station" placeholder="Type in a station name">
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="sell-method" id="sellsell-method">
                                            I will sell my items to buy orders
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="sell-method" id="sell-method" checked>
                                            I will sell my items to sell orders
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="buyer">Seller character: </label>
                                        <select class="form-control" name="buyer">
                                          
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="form-group">
                                        <label for="stocklist">Stock List: </label>
                                        <select class="form-control" name="stocklist">
                                          <option value="default">Select a Stock List</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="stocklist">Trade Route (optional): </label>
                                        <select class="form-control" name="stocklist">
                                          <option value="default">Select a Trade Route</option>
                                        </select>
                                    </div>
                                    <p><i class="fa fa-info"></i> Trade Routes will auto-fill starting and destination stations</p>
                                </div>
                            </div>
                            <div class="row text-center">
                                <button class="btn btn-default">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
