<!-- Header-->
<nav class="navbar navbar-default navbar-fixed-top" data-id="<?=$character_id?>" data-url="<?=base_url()?>">
    <div class="container-fluid">
        <div class="navbar-header">
            <div id="mobile-menu">
                <div class="left-nav-toggle">
                    <a href="#">
                        <i class="stroke-hamburgermenu">
                        </i>
                    </a>
                </div>
            </div>
            <a class="navbar-brand nav-u" data-selected="<?=$selected?>" href="<?=base_url('Updater')?>">
                ETM
                <span>
                    v.2.0
                </span>
                <i class="fa fa-refresh">
                </i>
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar">
            <div class="left-nav-toggle">
                <a href="#">
                    <i class="stroke-hamburgermenu">
                    </i>
                </a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="profil-link" data-aggr="<?=$aggregate?>" data-character="<?=$character_id?>" data-url="<?=base_url()?>">
                    <a href="<?=base_url('Updater')?>">
                        <span class="profile-address">
                            <i class="pe-7s-piggy">
                            </i>
                            <span class="header-balance" title="balance">
                            </span>
                            |
                            <i class="pe-7s-plugin">
                            </i>
                            <span class="header-networth" title="assets">
                            </span>
                            |
                            <i class="pe-7s-cart">
                            </i>
                            <span class="header-orders" title="sell orders">
                            </span>
                            |
                            <i class="pe-7s-culture">
                            </i>
                            <span class="header-escrow" title="escrow">
                            </span>
                        </span>
                        <i class="fa fa-refresh"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End header-->
<!-- Navigation-->
<aside class="navigation">
    <nav>
        <ul class="nav luna-nav">
            <li class="nav-category">
                Profit Tracking
            </li>
            <li class="dashboard">
                <a href="<?=base_url('Dashboard/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Dashboard
                </a>
            </li>
            <li class="profits">
                <a href="<?=base_url('Profits/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Profit Breakdown
                </a>
            </li>
            <li class="statistics">
                <a href="<?=base_url('Statistics/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Statistics
                </a>
            </li>
            <li class="nav-category">
                Information
            </li>
            <li class="transactions">
                <a href="<?=base_url('Transactions/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Transactions
                </a>
            </li>
            <li class="marketorders">
                <a href="<?=base_url('MarketOrders/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Market Orders
                </a>
            </li>
            <li class="contracts">
                <a href="<?=base_url('Contracts/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Contracts
                </a>
            </li>
            <li class="nav-category">
                Net worth
            </li>
            <li class="assets">
                <a href="<?=base_url('Assets/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Assets
                </a>
            </li>
            <li class="networth">
                <a href="<?=base_url('NetworthTracker/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Net worth Tracker
                </a>
            </li>
            <li class="nav-category">
                Trade Assistant
            </li>
            <li class="tradesimulator">
                <a href="<?=base_url('TradeSimulator/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Trade Simulator
                </a>
            </li>
            <li class="stocklists">
                <a href="<?=base_url('StockLists/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Stock Lists
                </a>
            </li>
            <li class="traderoutes">
                <a href="<?=base_url('TradeRoutes/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Trade Routes
                </a>
            </li>
            <li class="nav-category">
                Options
            </li>
            <li class="citadeltax">
                <a href="<?=base_url('CitadelTax/index/'.$character_id .'?aggr='.$aggregate)?>">
                    Citadel Taxes
                </a>
                
            </li>
            <li>
                <a aria-expanded="false" data-toggle="collapse" href="#api">
                    API Key Management
                    <span class="sub-nav-icon">
                        <i class="stroke-arrow">
                        </i>
                    </span>
                </a>
                <ul class="nav nav-second collapse" id="api">
                    <li class="api-add">
                        <a href="<?=base_url('API/add/'.$character_id .'?aggr='.$aggregate)?>">
                            Add Key/character
                        </a>
                    </li>
                    <li class="api-remove">
                        <a href="<?=base_url('API/remove/'.$character_id .'?aggr='.$aggregate)?>">
                            Remove Key/character
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>
<!-- End navigation-->
