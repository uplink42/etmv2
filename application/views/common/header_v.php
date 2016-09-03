<!-- Header-->
<script src="<?=base_url('assets/js/header.js')?>"></script>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
	<div class="navbar-header">
	    <div id="mobile-menu">
		<div class="left-nav-toggle">
		    <a href="#">
			<i class="stroke-hamburgermenu"></i>
		    </a>
		</div>
	    </div>
	    <a class="navbar-brand" href="<?=base_url('Updater')?>" data-selected="<?=$selected?>"> 
		ETM
		<span>v.2.0</span>
	    </a>
	</div>
	<div id="navbar" class="navbar-collapse collapse">
	    <div class="left-nav-toggle">
		<a href="#">
		    <i class="stroke-hamburgermenu"></i>
		</a>
	    </div>
	    <ul class="nav navbar-nav navbar-right">

		<li class="profil-link" data-character ="<?=$character_id?>" data-url="<?=base_url()?>" data-aggr="<?=$aggregate?>">
		    <a>
			<span class="profile-address">
			    <i class="pe-7s-piggy"></i> Wallet: <span class="header-balance"></span>|
			    <i class="pe-7s-plugin"></i> Assets: <span class="header-networth"></span>|
			    <i class="pe-7s-cart"></i> Market Orders: <span class="header-orders"></span>| 
			    <i class="pe-7s-culture"></i> Escrow: <span class="header-escrow"></span>
			</span>

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
		<a href="<?=base_url('Dashboard/index/'.$character_id .'?aggr='.$aggregate)?>">Dashboard</a>
	    </li>
	    <li class="profit">
		<a href="<?=base_url('Profit/index/'.$character_id .'?aggr='.$aggregate)?>">Profit Breakdown</a>
	    </li>
	    <li class="statistics">
		<a href="<?=base_url('Statistics/index/'.$character_id .'?aggr='.$aggregate)?>">Statistics</a>
	    </li>

	    <li class="nav-category">
		Information
	    </li>
	    <li class="transactions">
		<a href="<?=base_url('Transactions/index/'.$character_id .'?aggr='.$aggregate)?>"> Transactions </a>
	    </li>
	    <li class="marketorders">
		<a href="<?=base_url('MarketOrders/index/'.$character_id .'?aggr='.$aggregate)?>"> Market Orders </a>
	    </li>
	    <li class="contracts">
		<a href="<?=base_url('Contracts/index/'.$character_id .'?aggr='.$aggregate)?>"> Contracts </a>
	    </li>

	    <li class="nav-category">
		Net worth
	    </li>
	    <li class="assets">
		<a href="<?=base_url('Assets/index/'.$character_id .'?aggr='.$aggregate)?>"> Assets </a>
	    </li>
	    <li class="networth">
		<a href="<?=base_url('Net_worth/index/'.$character_id .'?aggr='.$aggregate)?>"> Net worth evolution </a>
	    </li>

	    <li class="nav-category">
		Trade Assistant
	    </li>
	    <li class="tradesimulator">
		<a href="<?=base_url('Trade_simulator/index/'.$character_id .'?aggr='.$aggregate)?>"> Trade Simulator </a>
	    </li>
	    <li class="stocklists">
		<a href="<?=base_url('Stock_lists/index/'.$character_id .'?aggr='.$aggregate)?>"> Stock Lists </a>
	    </li>
	    <li class="traderoutes">
		<a href="<?=base_url('Trade_routes/index/'.$character_id .'?aggr='.$aggregate)?>"> Trade Routes </a>
	    </li>

	    <li class="nav-category">
		Options
	    </li>
	    <li class="settings">
		<a href="<?=base_url('Settings/reports/'.$character_id .'?aggr='.$aggregate)?>">Account Settings</a>
	    </li>
	    <li>
		<a href="#api" data-toggle="collapse" aria-expanded="false">
		    API Key Management<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
		</a>
		<ul id="api" class="nav nav-second collapse">
		    <li class="api-add"><a href="<?=base_url('API/add/'.$character_id .'?aggr='.$aggregate)?>"> Add Key/character</a></li>
		    <li class="api-remove"><a href="<?=base_url('API/remove/'.$character_id .'?aggr='.$aggregate)?>"> Remove Key/character</a></li>
		</ul>
	    </li>

	</ul>
    </nav>
</aside>
    <!-- End navigation-->