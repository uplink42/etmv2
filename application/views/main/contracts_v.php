<script src="<?=base_url('assets/js/contracts-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>"></script>
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
                                <a href="<?=base_url('Contracts/index/' . $character_list['chars'][$i]) . '?aggr=0'?>">
                                    <?=$character_list['char_names'][$i]?>
                                </a>
                            </li>
                            <?php
}
?>
                            <li class="divider" role="separator">
                            </li>
                            <?php $url = "Contracts/index/" . $character_id . "?aggr=1" ;?>
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
                            <i class="pe page-header-icon pe-7s-news-paper">
                            </i>
                            <?php }?>
                    </div>
                    <div class="header-title">
                        <h1>
                            <?php echo $aggregate == 1 ? implode(' + ', $char_names) : $character_name?>
                            's Contracts
                        </h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <div class="dropdown pull-right">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-interval" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Type
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-contracts-active">
                                <li><a href="<?=base_url('Contracts/index/'.$character_id.'?active=all&inactive='.$inactives_filter.'&aggr='.$aggregate)?>">All</a></li>
                                <li><a href="<?=base_url('Contracts/index/'.$character_id.'?active=ItemExchange&inactive='.$inactives_filter.'&aggr='.$aggregate)?>">Item Exchange</a></li>
                                <li><a href="<?=base_url('Contracts/index/'.$character_id.'?active=Courier&inactive='.$inactives_filter.'&aggr='.$aggregate)?>">Courier</a></li>
                                <li><a href="<?=base_url('Contracts/index/'.$character_id.'?active=Loan&inactive='.$inactives_filter.'&aggr='.$aggregate)?>">Loan</a></li>
                                <li><a href="<?=base_url('Contracts/index/'.$character_id.'?active=Auction&inactive='.$inactives_filter.'&aggr='.$aggregate)?>">Auction</a></li>
                            </ul>
                        </div>
                        <div class="panel-tools">
                        </div>
                    </div>
                    <br>
                    <div class="panel-body contracts-active-body">
                        <h4>Active <?=$actives_filter?> Contracts</h4>
                        <p class="yellow"></p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="contracts-active-table">
                                <thead>
                                    <tr>
                                        <th>Creation Time</th>
                                        <th>Expiration Time</th>
                                        <th>Issuer</th>
                                        <th>Acceptor</th>
                                        <th>Availability</th>
                                        <th>Price</th>
                                        <th>Reward</th>
                                        <th>Type</th>
                                        <th>State</th>
                                        <th>Station</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                foreach($actives as $row) {
                                    switch($row['type']) {
                                        case 'ItemExchange':
                                            $icon = "<i class='fa fa-eur'></i> ";
                                            break;
                                        case 'Courier':
                                            $icon = "<i class='fa fa-truck'></i> ";
                                            break;
                                        case 'Loan':
                                            $icon = "<i class='fa fa-credit-card-alt'></i> ";
                                            break;
                                        case 'Auction':
                                            $icon = "<i class='fa fa-bank'></i> ";
                                            break;
                                    }
                                    ?>
                                    <tr>
                                        <td><?=$row['creation']?></td>
                                        <td><?=$row['expiration']?></td>
                                        <td><?=$row['issuer_name']?></td>
                                        <td><?=$row['acceptor_name']?></td>
                                        <td><?=$row['avail']?></td>
                                        <td><?=number_format($row['price'],2)?></td>
                                        <td><?=number_format($row['reward'],2)?></td>
                                        <td><?=$icon . $row['type']?></td>
                                        <td><?=$row['status']?></td>
                                        <td><?=$row['station']?></td>
                                    </tr>

                                    <?php
                                    }?>
                                </tbody>
                            </table>
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
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-contracts-inactive" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Type
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-contracts">
                                <li><a href="<?=base_url('Contracts/index/'.$character_id.'?inactive=all&active='.$actives_filter.'&aggr='.$aggregate)?>">All</a></li>
                                <li><a href="<?=base_url('Contracts/index/'.$character_id.'?inactive=ItemExchange&active='.$actives_filter.'&aggr='.$aggregate)?>">Item Exchange</a></li>
                                <li><a href="<?=base_url('Contracts/index/'.$character_id.'?inactive=Courier&active='.$actives_filter.'&aggr='.$aggregate)?>">Courier</a></li>
                                <li><a href="<?=base_url('Contracts/index/'.$character_id.'?inactive=Loan&active='.$actives_filter.'&aggr='.$aggregate)?>">Loan</a></li>
                                <li><a href="<?=base_url('Contracts/index/'.$character_id.'?inactive=Auction&active='.$actives_filter.'&aggr='.$aggregate)?>">Auction</a></li>
                            </ul>
                        </div>
                        <div class="panel-tools">
                        </div>
                    </div>
                    <br>
                    <div class="panel-body contracts-inactive-body">
                        <h4>Inactive <?=$inactives_filter?> Contracts</h4>
                        <p class="yellow"></p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="contracts-inactive-table">
                                <thead>
                                    <tr>
                                        <th>Creation Time</th>
                                        <th>Expiration Time</th>
                                        <th>Issuer</th>
                                        <th>Acceptor</th>
                                        <th>Availability</th>
                                        <th>Price</th>
                                        <th>Reward</th>
                                        <th>Type</th>
                                        <th>State</th>
                                        <th>Station</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                foreach($inactives as $row) {
                                    switch($row['type']) {
                                        case 'ItemExchange':
                                            $icon = "<i class='fa fa-eur'></i> ";
                                            break;
                                        case 'Courier':
                                            $icon = "<i class='fa fa-truck'></i> ";
                                            break;
                                        case 'Loan':
                                            $icon = "<i class='fa fa-credit-card-alt'></i> ";
                                            break;
                                        case 'Auction':
                                            $icon = "<i class='fa fa-bank'></i> ";
                                            break;
                                    }
                                    ?>
                                    <tr>
                                        <td><?=$row['creation']?></td>
                                        <td><?=$row['expiration']?></td>
                                        <td><?=$row['issuer_name']?></td>
                                        <td><?=$row['acceptor_name']?></td>
                                        <td><?=$row['avail']?></td>
                                        <td><?=number_format($row['price'],2)?></td>
                                        <td><?=number_format($row['reward'],2)?></td>
                                        <td><?=$icon . $row['type']?></td>
                                        <td><?=$row['status']?></td>
                                        <td><?=$row['station']?></td>
                                    </tr>

                                    <?php
                                    }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
